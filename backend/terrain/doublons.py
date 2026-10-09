"""
Contrôle des doublons clients.

Un même client peut être saisi plusieurs fois : rien ne l'empêche à la saisie.
Ce module regroupe les fiches qui désignent vraisemblablement la même
personne, puis qualifie chaque fiche par rapport à la première saisie :

- originale : la première fiche du groupe ;
- re-saisie « autre commercial » : le client avait déjà été enregistré par un
  autre commercial — le cas qui gonfle artificiellement les chiffres ;
- re-saisie « même commercial » : double saisie, ou carte supplémentaire
  vendue au même client.

L'analyse porte sur toutes les fiches du périmètre (quelques milliers) et se
fait en Python : la normalisation des numéros et des noms n'a pas
d'équivalent SQL portable.
"""

import re
import unicodedata
from dataclasses import dataclass, field
from datetime import datetime

#: Critères de regroupement proposés à l'écran.
CRITERE_NUMERO = "numero"
CRITERE_NOM = "nom"
CRITERE_NUMERO_ET_NOM = "numero_et_nom"
CRITERE_NUMERO_OU_NOM = "numero_ou_nom"
CRITERES = {
    CRITERE_NUMERO_ET_NOM: "Même numéro et même nom (doublon certain)",
    CRITERE_NUMERO: "Même numéro de téléphone",
    CRITERE_NOM: "Même nom et prénom",
    CRITERE_NUMERO_OU_NOM: "Même numéro ou même nom",
}

#: Ce qu'on garde des groupes trouvés.
CAS_TOUS = "tous"
CAS_AUTRE = "autre"
CAS_VICTIME = "victime"
CAS_MEME = "meme"
CAS = {
    CAS_TOUS: "Tous les doublons",
    CAS_AUTRE: "A ressaisi le client d'un autre commercial",
    CAS_VICTIME: "Son client a été ressaisi par un autre commercial",
    CAS_MEME: "A ressaisi son propre client",
}

#: Délai entre la 1ère saisie et la re-saisie, en jours calendaires.
DELAIS = {
    "0": ("Le même jour", 0, 0),
    "1-7": ("1 à 7 jours après", 1, 7),
    "8-30": ("8 à 30 jours après", 8, 30),
    "31+": ("Plus de 30 jours après", 31, None),
}

CAMPAGNES_RESAISIE = {
    "meme": "Dans la même campagne que la 1ère saisie",
    "autre": "Dans une autre campagne que la 1ère saisie",
}

TRIS = {
    "taille": "Clients les plus ressaisis d'abord",
    "recent": "Re-saisies les plus récentes d'abord",
    "ancien": "Re-saisies les plus anciennes d'abord",
    "delai": "Plus long délai d'abord",
}


ORIGINALE = "originale"
RESAISIE_AUTRE = "resaisie_autre"
RESAISIE_MEME = "resaisie_meme"


def cle_numero(telephone):
    """Les 8 derniers chiffres : « 76 12 34 56 » et « +223 76123456 » se confondent."""
    chiffres = re.sub(r"\D", "", telephone or "")[-8:]
    return chiffres if len(chiffres) == 8 else None


def cle_nom(prenom, nom):
    """Prénom et nom sans accents ni casse, mots triés : « DIARRA Moussa » = « Moussa Diarra »."""
    texte = unicodedata.normalize("NFKD", f"{prenom or ''} {nom or ''}")
    texte = texte.encode("ascii", "ignore").decode().lower()
    mots = sorted(re.findall(r"[a-z]+", texte))
    return " ".join(mots) if len(mots) >= 2 else None


@dataclass
class Fiche:
    id: int
    prenom: str
    nom: str
    telephone: str
    user_id: int
    created_at: datetime
    campagne_id: int = None
    groupe: int = 0
    rang: int = 0
    statut: str = ORIGINALE
    premiere: "Fiche" = None
    #: Re-saisie retenue par les filtres d'audit : c'est elle qu'on examine.
    cible: bool = False

    @property
    def delai(self):
        """Jours calendaires écoulés depuis la 1ère saisie du client."""
        if self.rang <= 1 or not self.created_at or not self.premiere.created_at:
            return None
        return (self.created_at.date() - self.premiere.created_at.date()).days


@dataclass
class Groupe:
    numero: int
    cle: str
    fiches: list = field(default_factory=list)

    @property
    def commerciaux(self):
        return {f.user_id for f in self.fiches}

    def contient(self, statut):
        return any(f.statut == statut for f in self.fiches)


class _UnionFind:
    def __init__(self):
        self.parent = {}

    def trouver(self, x):
        self.parent.setdefault(x, x)
        while self.parent[x] != x:
            self.parent[x] = self.parent[self.parent[x]]
            x = self.parent[x]
        return x

    def unir(self, a, b):
        ra, rb = self.trouver(a), self.trouver(b)
        if ra != rb:
            self.parent[rb] = ra


def analyser(fiches, critere=CRITERE_NUMERO):
    """
    Regroupe les fiches selon `critere` et qualifie chacune.

    Renvoie la liste des groupes d'au moins deux fiches, les plus gros
    d'abord. Les fiches de chaque groupe sont triées par date de saisie.
    """
    cles = []
    if critere in (CRITERE_NUMERO, CRITERE_NUMERO_OU_NOM):
        cles.append(lambda f: ("tel", cle_numero(f.telephone)))
    if critere in (CRITERE_NOM, CRITERE_NUMERO_OU_NOM):
        cles.append(lambda f: ("nom", cle_nom(f.prenom, f.nom)))
    if critere == CRITERE_NUMERO_ET_NOM:
        def numero_et_nom(f):
            tel, nom = cle_numero(f.telephone), cle_nom(f.prenom, f.nom)
            return ("tel+nom", (tel, nom) if tel and nom else None)

        cles.append(numero_et_nom)

    # Union-find : avec « numéro ou nom », deux fiches reliées par un numéro
    # et une troisième reliée à l'une d'elles par le nom forment un seul groupe.
    uf = _UnionFind()
    premiere_par_cle = {}
    for f in fiches:
        uf.trouver(f.id)
        for cle in cles:
            k = cle(f)
            if k[1] is None:
                continue
            if k in premiere_par_cle:
                uf.unir(premiere_par_cle[k], f.id)
            else:
                premiere_par_cle[k] = f.id

    par_racine = {}
    for f in fiches:
        par_racine.setdefault(uf.trouver(f.id), []).append(f)

    groupes = []
    for membres in par_racine.values():
        if len(membres) < 2:
            continue
        membres.sort(key=lambda f: (f.created_at or datetime.min, f.id))
        premiere = membres[0]
        for rang, f in enumerate(membres):
            f.rang = rang + 1
            f.premiere = premiere
            if rang == 0:
                f.statut = ORIGINALE
            elif any(p.user_id != f.user_id for p in membres[:rang]):
                f.statut = RESAISIE_AUTRE
            else:
                f.statut = RESAISIE_MEME
        cle = cle_numero(premiere.telephone) if critere != CRITERE_NOM else None
        groupes.append(
            Groupe(0, cle or cle_nom(premiere.prenom, premiere.nom) or "", membres)
        )

    groupes.sort(key=lambda g: (-len(g.fiches), g.fiches[0].created_at or datetime.min))
    for i, g in enumerate(groupes, 1):
        g.numero = i
        for f in g.fiches:
            f.groupe = i
    return groupes


def statistiques(groupes):
    resaisies = [f for g in groupes for f in g.fiches if f.cible]
    return {
        "groupes": len(groupes),
        "fiches": sum(len(g.fiches) for g in groupes),
        "resaisies_autre": sum(f.statut == RESAISIE_AUTRE for f in resaisies),
        "resaisies_meme": sum(f.statut == RESAISIE_MEME for f in resaisies),
        "plusieurs_commerciaux": sum(len(g.commerciaux) > 1 for g in groupes),
    }


def classement_commerciaux(resaisies, totaux_par_user):
    """Par auteur de re-saisie : combien, et rapporté à son total de fiches."""
    par_user = {}
    for f in resaisies:
        ligne = par_user.setdefault(f.user_id, {"user_id": f.user_id, "autre": 0, "meme": 0})
        ligne["autre" if f.statut == RESAISIE_AUTRE else "meme"] += 1
    lignes = []
    for ligne in par_user.values():
        total = totaux_par_user.get(ligne["user_id"], 0)
        ligne["total_fiches"] = total
        ligne["resaisies"] = ligne["autre"] + ligne["meme"]
        ligne["pourcentage"] = round(100 * ligne["resaisies"] / total, 1) if total else 0
        lignes.append(ligne)
    # Du plus fort pourcentage de re-saisies au plus faible ; à égalité, celui
    # qui a repris le plus de clients à d'autres commerciaux d'abord.
    lignes.sort(key=lambda l: (-l["pourcentage"], -l["autre"], -l["resaisies"]))
    return lignes


def tranche_delai(jours):
    for code, (_, mini, maxi) in DELAIS.items():
        if jours is not None and jours >= mini and (maxi is None or jours <= maxi):
            return code
    return None


def audit_commercial(groupes, user_id, ids_filtres):
    """
    Bilan d'un commercial : ce qu'il a ressaisi, et ce que les autres lui ont
    ressaisi. Seules comptent ses fiches du périmètre filtré (campagne,
    période, agence…) ; les filtres propres aux re-saisies (cas, délai) n'y
    entrent pas, pour que les cases du bilan restent comparables.
    """
    fait_autre, fait_meme, subi = [], [], []
    for g in groupes:
        premiere = g.fiches[0]
        for f in g.fiches[1:]:
            if f.user_id == user_id and f.id in ids_filtres:
                (fait_autre if f.statut == RESAISIE_AUTRE else fait_meme).append(f)
            elif premiere.user_id == user_id and premiere.id in ids_filtres and f.user_id != user_id:
                subi.append(f)

    def compter(fiches, cle):
        c = {}
        for f in fiches:
            k = cle(f)
            c[k] = c.get(k, 0) + 1
        return sorted(c.items(), key=lambda x: -x[1])

    faites = fait_autre + fait_meme
    return {
        "resaisies_autre": len(fait_autre),
        "resaisies_meme": len(fait_meme),
        "clients_ressaisis_par_autres": len(subi),
        # user_id des commerciaux dont il a repris les clients, et inversement.
        "pris_a": compter(fait_autre, lambda f: f.premiere.user_id),
        "pris_par": compter(subi, lambda f: f.user_id),
        "par_campagne": compter(faites, lambda f: f.campagne_id),
        "par_delai": compter(faites, lambda f: tranche_delai(f.delai)),
        "par_mois": sorted(
            compter(faites, lambda f: f.created_at.strftime("%Y-%m") if f.created_at else None),
            key=lambda x: x[0] or "",
        ),
        "premiere": min((f.created_at for f in faites if f.created_at), default=None),
        "derniere": max((f.created_at for f in faites if f.created_at), default=None),
    }
