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
CRITERE_NUMERO_OU_NOM = "numero_ou_nom"
CRITERES = {
    CRITERE_NUMERO: "Même numéro de téléphone",
    CRITERE_NOM: "Même nom et prénom",
    CRITERE_NUMERO_OU_NOM: "Même numéro ou même nom",
}

#: Ce qu'on garde des groupes trouvés.
CAS_TOUS = "tous"
CAS_AUTRE = "autre"
CAS_MEME = "meme"
CAS = {
    CAS_TOUS: "Tous les doublons",
    CAS_AUTRE: "Client déjà enregistré par un autre commercial",
    CAS_MEME: "Client ressaisi par le même commercial",
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
    groupe: int = 0
    rang: int = 0
    statut: str = ORIGINALE
    premiere: "Fiche" = None


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


def garder(groupes, cas):
    if cas == CAS_AUTRE:
        return [g for g in groupes if g.contient(RESAISIE_AUTRE)]
    if cas == CAS_MEME:
        return [g for g in groupes if g.contient(RESAISIE_MEME)]
    return groupes


def statistiques(groupes):
    fiches = [f for g in groupes for f in g.fiches]
    return {
        "groupes": len(groupes),
        "fiches": len(fiches),
        "resaisies_autre": sum(f.statut == RESAISIE_AUTRE for f in fiches),
        "resaisies_meme": sum(f.statut == RESAISIE_MEME for f in fiches),
        "plusieurs_commerciaux": sum(len(g.commerciaux) > 1 for g in groupes),
    }


def classement_commerciaux(fiches, totaux_par_user):
    """Par commercial : ses re-saisies, rapportées à son total de fiches."""
    par_user = {}
    for f in fiches:
        ligne = par_user.setdefault(f.user_id, {"user_id": f.user_id, "autre": 0, "meme": 0})
        if f.statut == RESAISIE_AUTRE:
            ligne["autre"] += 1
        elif f.statut == RESAISIE_MEME:
            ligne["meme"] += 1
    lignes = []
    for ligne in par_user.values():
        resaisies = ligne["autre"] + ligne["meme"]
        if not resaisies:
            continue
        total = totaux_par_user.get(ligne["user_id"], 0)
        ligne["total_fiches"] = total
        ligne["resaisies"] = resaisies
        ligne["pourcentage"] = round(100 * resaisies / total, 1) if total else 0
        lignes.append(ligne)
    lignes.sort(key=lambda l: (-l["autre"], -l["resaisies"]))
    return lignes
