"""
Services terrain : périmètre statistique des campagnes, enregistrement des
ventes et des enrôlements, données du contrat de prestation.

Portage de app/Services/{CampagneStatsScope,VenteService,EnrolementService,
ContratPrestationService}.php.
"""

from datetime import date, datetime, timedelta

from django.db import transaction

from campagnes.models import Campagne, TypeCampagne
from core.models import Role, TypeCarte, User

from .models import Client, EnrolementClient, StatutCarte, Vente


class ErreurMetier(Exception):
    """Règle métier non respectée — équivalent d'InvalidArgumentException."""


# ---------------------------------------------------------------------------
# Périmètre statistique
# ---------------------------------------------------------------------------


def campagnes_pour_stats(agence_id=None, type_campagne=None):
    campagnes = Campagne.campagnes_pour_stats(agence_id)
    if type_campagne is None:
        return campagnes
    return [c for c in campagnes if c.type == type_campagne]


def ids_pour_stats(agence_id=None, type_campagne=None):
    return [c.id for c in campagnes_pour_stats(agence_id, type_campagne)]


def restreindre_aux_campagnes_vente(queryset, agence_id=None):
    """
    Borne une requête aux campagnes de vente du périmètre.

    Une requête sur `ventes` ne doit jamais être bornée à une campagne
    d'enrôlement : elle renverrait un ensemble vide tout en affichant le nom de
    cette campagne comme périmètre.
    """
    ids = ids_pour_stats(agence_id, TypeCampagne.VENTE_CARTE)
    return queryset.filter(campagne_id__in=ids) if ids else queryset.none()


def libelle_stats(agence_id=None, type_campagne=None):
    campagnes = campagnes_pour_stats(agence_id, type_campagne)
    if not campagnes:
        return "Aucune campagne"
    noms = [f"« {c.nom} »" for c in campagnes]
    return noms[0] if len(noms) == 1 else ", ".join(noms[:-1]) + " et " + noms[-1]


# ---------------------------------------------------------------------------
# Campagnes ouvertes pour un commercial
# ---------------------------------------------------------------------------


def campagnes_ouvertes_pour(user, type_campagne):
    """Campagnes actives de ce type où le commercial est réellement engagé."""
    if not user.agence_id:
        return []
    return [
        campagne
        for campagne in Campagne.actives_pour_agence(int(user.agence_id)).filter(
            type=type_campagne
        )
        if campagne.est_engage_commercial(user.id)
    ]


def _resoudre_campagne(ouvertes, campagne_id_demande, libelle_type):
    """
    Détermine la campagne d'imputation.

    Si plusieurs campagnes sont ouvertes, le commercial doit choisir ; sinon la
    seule campagne ouverte s'impose, et un identifiant contradictoire est refusé.
    """
    if len(ouvertes) > 1:
        if not campagne_id_demande:
            raise ErreurMetier(
                f"Plusieurs campagnes{libelle_type} sont ouvertes : indiquez la campagne "
                "(sélection sur le formulaire)."
            )
        campagne = next((c for c in ouvertes if c.id == campagne_id_demande), None)
        if campagne is None:
            raise ErreurMetier("Campagne non reconnue ou non ouverte pour votre agence.")
        return campagne

    campagne = ouvertes[0]
    if campagne_id_demande and int(campagne.id) != campagne_id_demande:
        raise ErreurMetier("Campagne non reconnue ou non ouverte pour votre agence.")
    return campagne


def _verifier_commercial(user, action):
    if user.role != Role.COMMERCIAL or not user.agence_id:
        raise ErreurMetier(
            f"Seul un commercial avec une agence peut enregistrer {action}."
        )
    if not user.actif:
        raise ErreurMetier(
            f"Compte commercial désactivé. Vous ne pouvez pas enregistrer {action}."
        )


def enregistrer_vente(donnees, user):
    """Portage de VenteService::enregistrerVente() : crée le client puis la vente."""
    _verifier_commercial(user, "une vente")

    type_carte_id = int(donnees.get("type_carte_id") or 0)
    if not TypeCarte.objects.filter(id=type_carte_id, actif=True).exists():
        raise ErreurMetier("Type de carte invalide ou inactif.")

    agence_id = int(user.agence_id)
    Campagne.sync_statuts()
    ouvertes = campagnes_ouvertes_pour(user, TypeCampagne.VENTE_CARTE)
    if not ouvertes:
        raise ErreurMetier(
            "Aucune campagne en cours pour votre agence. Les ventes ne sont possibles que "
            "pendant une campagne active ; une campagne terminée ou arrêtée ne permet plus "
            "d’enregistrer de vente."
        )

    campagne = _resoudre_campagne(
        ouvertes, int(donnees["campagne_id"]) if donnees.get("campagne_id") else None, ""
    )

    if not campagne.est_ouverte(agence_id):
        raise ErreurMetier(
            "Cette campagne n’accepte pas les ventes pour votre agence pour le moment."
        )
    if not campagne.commercial_a_accepte_contrat(user.id):
        raise ErreurMetier(
            f"Vous devez d’abord accepter le contrat de prestation de la campagne "
            f"« {campagne.nom} » (rubrique « Mon contrat ») avant de pouvoir enregistrer une vente."
        )

    with transaction.atomic():
        client = Client.objects.create(
            prenom=donnees["prenom"],
            nom=donnees["nom"],
            telephone=donnees.get("telephone") or None,
            ville=donnees.get("ville") or None,
            quartier=donnees.get("quartier") or None,
            type_carte_id=type_carte_id,
            statut_carte=StatutCarte.VENDUE,
            carte_identite=donnees.get("carte_identite") or None,
            user_id=user.id,
        )
        return Vente.objects.create(
            client_id=client.id,
            user_id=user.id,
            agence_id=agence_id,
            campagne_id=campagne.id,
            type_carte_id=type_carte_id,
            statut_activation=StatutCarte.VENDUE,
        )


def enregistrer_enrolement(donnees, user):
    """Portage de EnrolementService::enregistrerEnrolement()."""
    _verifier_commercial(user, "un enrôlement")

    agence_id = int(user.agence_id)
    Campagne.sync_statuts()
    ouvertes = campagnes_ouvertes_pour(user, TypeCampagne.ENROLEMENT_APP)
    if not ouvertes:
        raise ErreurMetier("Aucune campagne d’enrôlement en cours pour votre agence.")

    campagne = _resoudre_campagne(
        ouvertes,
        int(donnees["campagne_id"]) if donnees.get("campagne_id") else None,
        " d’enrôlement",
    )

    if not campagne.est_ouverte(agence_id):
        raise ErreurMetier(
            "Cette campagne n’accepte pas d’enrôlements pour votre agence pour le moment."
        )
    if not campagne.commercial_a_accepte_contrat(user.id):
        raise ErreurMetier(
            f"Vous devez d’abord accepter le contrat de prestation de la campagne "
            f"« {campagne.nom} » (rubrique « Mon contrat ») avant de pouvoir enregistrer un enrôlement."
        )

    return EnrolementClient.objects.create(
        campagne_id=campagne.id,
        user_id=user.id,
        agence_id=agence_id,
        nom=donnees["nom"],
        prenom=donnees["prenom"],
        numero_compte=donnees["numero_compte"],
        telephone=donnees.get("telephone") or None,
        adresse=donnees.get("adresse") or None,
    )


# ---------------------------------------------------------------------------
# Contrat de prestation
# ---------------------------------------------------------------------------


def donnees_contrat(campagne):
    """
    Éléments variables du document contractuel.

    Le contrat court à partir du lundi de la semaine de démarrage, même si la
    campagne commence en cours de semaine.
    """
    lundi = campagne.date_debut
    if lundi.weekday() != 0:
        lundi = lundi - timedelta(days=lundi.weekday())

    return {
        "lundi_effectif": lundi,
        "date_signature_affichee": date.today().strftime("%d/%m/%Y"),
    }
