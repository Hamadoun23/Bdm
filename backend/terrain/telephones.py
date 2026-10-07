"""
Numéros de téléphone des clients : format international et unicité.

Règles en vigueur depuis le 07/10/2026, pour empêcher la fraude :

- le numéro est saisi avec l'indicatif du pays et enregistré au format
  international compact (`+22376123456`) ;
- un numéro n'appartient qu'à une seule personne : il est refusé s'il est déjà
  enregistré, en vente ou en enrôlement, au nom de quelqu'un d'autre ;
- une vente à un client déjà enregistré n'est pas enregistrée directement :
  elle devient une demande que l'administrateur valide ou refuse
  (`DemandeClientExistant`) ; un client ne peut pas être enrôlé deux fois.

La comparaison se fait sur les 8 derniers chiffres : les fiches anciennes,
saisies sans indicatif, sont ainsi reconnues.
"""

import re

from .doublons import cle_nom, cle_numero

#: Indicatif → (pays, nombre de chiffres du numéro national).
INDICATIFS = {
    "223": ("Mali", 8),
    "221": ("Sénégal", 9),
    "222": ("Mauritanie", 8),
    "224": ("Guinée", 9),
    "225": ("Côte d'Ivoire", 10),
    "226": ("Burkina Faso", 8),
    "227": ("Niger", 8),
    "228": ("Togo", 8),
    "229": ("Bénin", 10),
    "233": ("Ghana", 9),
    "234": ("Nigeria", 10),
    "237": ("Cameroun", 9),
    "213": ("Algérie", 9),
    "212": ("Maroc", 9),
    "33": ("France", 9),
}

EXEMPLE = "+223 76 12 34 56"


class NumeroInvalide(ValueError):
    pass


def normaliser(valeur):
    """
    « +223 76 12 34 56 », « 00223-76123456 » → « +22376123456 ».

    Lève NumeroInvalide si l'indicatif manque ou si la longueur ne correspond
    pas au pays.
    """
    brut = (valeur or "").strip()
    if not brut:
        raise NumeroInvalide("Le numéro de téléphone est obligatoire.")
    if re.search(r"[^\d\s+().-]", brut):
        raise NumeroInvalide("Le numéro ne doit contenir que des chiffres.")
    compact = re.sub(r"[\s().-]", "", brut)
    if compact.startswith("00"):
        compact = "+" + compact[2:]
    if not compact.startswith("+") or "+" in compact[1:]:
        raise NumeroInvalide(
            f"Ajoutez l'indicatif du pays devant le numéro (ex. {EXEMPLE})."
        )
    chiffres = compact[1:]
    for indicatif, (pays, longueur) in sorted(INDICATIFS.items(), key=lambda x: -len(x[0])):
        if chiffres.startswith(indicatif):
            national = chiffres[len(indicatif):]
            if len(national) != longueur:
                raise NumeroInvalide(
                    f"Un numéro {pays} (+{indicatif}) compte {longueur} chiffres "
                    f"après l'indicatif ; vous en avez saisi {len(national)}."
                )
            return "+" + chiffres
    if not 8 <= len(chiffres) <= 15:
        raise NumeroInvalide(f"Numéro international invalide (ex. {EXEMPLE}).")
    return "+" + chiffres


def _nom(user):
    if user is None:
        return "un autre commercial"
    return f"{user.prenom or ''} {user.name or ''}".strip() or user.name


def _meme_numero(queryset, cle):
    """Fiches dont le numéro a les mêmes 8 derniers chiffres."""
    candidats = queryset.filter(telephone__contains=cle[-4:]).select_related("user")
    return [x for x in candidats if cle_numero(x.telephone) == cle]


def verifier(telephone, prenom, nom, user, *, nature, exclure_id=None):
    """
    Contrôle d'unicité avant enregistrement. Renvoie un message d'erreur, ou
    None si la saisie est autorisée.

    `nature` vaut « vente » ou « enrolement » ; `exclure_id` est la fiche en
    cours de modification.
    """
    from .models import Client, EnrolementClient

    cle = cle_numero(telephone)
    personne = cle_nom(prenom, nom)
    if not cle:
        return None

    clients = Client.objects.all()
    enrolements = EnrolementClient.objects.all()
    if nature == "vente" and exclure_id:
        clients = clients.exclude(pk=exclure_id)
    if nature == "enrolement" and exclure_id:
        enrolements = enrolements.exclude(pk=exclure_id)

    fiches = [("vente", x, f"{x.prenom} {x.nom}") for x in _meme_numero(clients, cle)]
    fiches += [("enrolement", x, f"{x.prenom} {x.nom}") for x in _meme_numero(enrolements, cle)]
    fiches.sort(key=lambda t: t[1].created_at)

    for type_fiche, fiche, nom_complet in fiches:
        if cle_nom(nom_complet, "") != personne:
            quand = fiche.created_at.strftime("%d/%m/%Y")
            return (
                f"Ce numéro appartient déjà à « {nom_complet.strip()} » "
                f"({'client' if type_fiche == 'vente' else 'enrôlement'} enregistré par "
                f"{_nom(fiche.user)} le {quand}). Un numéro ne peut appartenir qu'à "
                "une seule personne : vérifiez le numéro ou le nom du client."
            )

    for type_fiche, fiche, _ in fiches:
        if type_fiche != nature:
            continue
        meme_reseau = fiche.user and fiche.user.partenaire_id == user.partenaire_id
        quand = fiche.created_at.strftime("%d/%m/%Y")
        if nature == "enrolement" and meme_reseau:
            return (
                f"Ce client est déjà enrôlé (par {_nom(fiche.user)} le {quand}). "
                "Un client ne peut être enrôlé qu'une seule fois."
            )
        # Correction d'une fiche : elle ne doit pas devenir le doublon d'un
        # client existant. Une nouvelle vente, elle, passe par une demande.
        if nature == "vente" and exclure_id and meme_reseau:
            return (
                f"Ce client est déjà enregistré par {_nom(fiche.user)} le {quand}. "
                "Pour lui vendre une autre carte, faites une nouvelle vente : "
                "elle sera soumise à l'administrateur."
            )
    return None


def clients_existants(telephone, prenom, nom, user):
    """
    Fiches clients de la même personne, chez le même partenaire, les plus
    anciennes d'abord. Une vente à l'un de ces clients exige la validation
    de l'administrateur.
    """
    from .models import Client

    cle = cle_numero(telephone)
    if not cle:
        return []
    personne = cle_nom(prenom, nom)
    fiches = [
        c for c in _meme_numero(Client.objects.select_related("type_carte"), cle)
        if cle_nom(c.prenom, c.nom) == personne
        and c.user
        and c.user.partenaire_id == user.partenaire_id
    ]
    return sorted(fiches, key=lambda c: c.created_at)


def verifier_compte(numero_compte, *, exclure_id=None):
    """Un numéro de compte ne peut être enrôlé qu'une fois."""
    from .models import EnrolementClient

    chiffres = re.sub(r"\D", "", numero_compte or "")
    if len(chiffres) < 6:
        return None
    candidats = EnrolementClient.objects.filter(
        numero_compte__contains=chiffres[-6:]
    ).select_related("user")
    if exclure_id:
        candidats = candidats.exclude(pk=exclure_id)
    for e in candidats:
        if re.sub(r"\D", "", e.numero_compte or "") == chiffres:
            return (
                f"Ce numéro de compte est déjà enrôlé au nom de « {e.prenom} {e.nom} » "
                f"(par {_nom(e.user)} le {e.created_at.strftime('%d/%m/%Y')})."
            )
    return None
