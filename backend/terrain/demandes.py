"""
Ventes à un client déjà enregistré : validation par l'administrateur.

Le commercial qui saisit une vente pour un client déjà présent dans la base
(même numéro, même personne) ne crée pas la vente : il envoie une demande
(`DemandeClientExistant`). L'administrateur la compare aux fiches existantes,
puis la valide — la vente est alors créée, datée du jour de la saisie — ou la
refuse avec un commentaire que le commercial voit dans « Mes demandes ».
"""

from datetime import datetime

from django.db import transaction
from django.shortcuts import get_object_or_404, redirect
from inertia import render

from core.decorators import http_methods, role_required
from core.middleware import deposer_flash
from core.models import Role
from core.pagination import paginer
from core.partenaires import filtrer_saisies, partenaire_courant
from core.php import tableau

from . import services
from .doublons import cle_numero
from .models import Client, DemandeClientExistant, EnrolementClient, StatutDemande

STATUTS = {s.value: s.label for s in StatutDemande}


def _nom(user):
    if user is None:
        return "—"
    return f"{user.prenom or ''} {user.name or ''}".strip() or user.name


def _date(valeur, avec_heure=True):
    if not valeur:
        return None
    return valeur.strftime("%d/%m/%Y %H:%M" if avec_heure else "%d/%m/%Y")


def _fiches_meme_numero(demande):
    """Toutes les saisies existantes au même numéro, pour comparer."""
    cle = cle_numero(demande.telephone)
    if not cle:
        return []
    lignes = []
    clients = (
        Client.objects.filter(telephone__contains=cle[-4:])
        .select_related("user", "type_carte")
        .prefetch_related("ventes__campagne", "ventes__agence")
    )
    for c in clients:
        if cle_numero(c.telephone) != cle:
            continue
        vente = next(iter(c.ventes.all()), None)
        lignes.append({
            "nature": "Vente",
            "id": c.id,
            "nom_complet": f"{c.prenom} {c.nom}".strip(),
            "telephone": c.telephone,
            "detail": c.type_carte.code if c.type_carte_id else "?",
            "meme_type_carte": c.type_carte_id == demande.type_carte_id,
            "commercial": _nom(c.user),
            "meme_commercial": c.user_id == demande.user_id,
            "agence": vente.agence.nom if vente and vente.agence_id else None,
            "campagne": vente.campagne.nom if vente and vente.campagne_id else None,
            "date": _date(c.created_at),
            "tri": c.created_at or datetime.min,
        })
    for e in EnrolementClient.objects.filter(telephone__contains=cle[-4:]).select_related(
        "user", "campagne", "agence"
    ):
        if cle_numero(e.telephone) != cle:
            continue
        lignes.append({
            "nature": "Enrôlement",
            "id": None,
            "nom_complet": e.nom_complet,
            "telephone": e.telephone,
            "detail": e.numero_compte or "—",
            "meme_type_carte": False,
            "commercial": _nom(e.user),
            "meme_commercial": e.user_id == demande.user_id,
            "agence": e.agence.nom if e.agence_id else None,
            "campagne": e.campagne.nom if e.campagne_id else None,
            "date": _date(e.created_at),
            "tri": e.created_at or datetime.min,
        })
    lignes.sort(key=lambda x: x.pop("tri"))
    return lignes


def _formater(demande, avec_comparaison):
    vente = demande.donnees.get("vente", {})
    ligne = {
        "id": demande.id,
        "statut": demande.statut,
        "statut_libelle": STATUTS.get(demande.statut, demande.statut),
        "nom_complet": demande.nom_complet,
        "telephone": demande.telephone,
        "ville": vente.get("ville"),
        "quartier": vente.get("quartier"),
        "type_carte": demande.type_carte.code if demande.type_carte_id else "?",
        "meme_type_carte": demande.meme_type_carte,
        "commercial": _nom(demande.user),
        "agence": demande.user.agence.nom if demande.user.agence_id else None,
        "campagne": demande.campagne.nom if demande.campagne_id else None,
        "motif": demande.motif,
        "date": _date(demande.created_at),
        "traite_par": _nom(demande.traite_par) if demande.traite_par_id else None,
        "traite_le": _date(demande.traite_le),
        "commentaire_admin": demande.commentaire_admin,
        "vente_id": demande.vente_id,
    }
    if avec_comparaison:
        ligne["fiches"] = _fiches_meme_numero(demande)
    return ligne


def _demandes(request):
    return filtrer_saisies(
        DemandeClientExistant.objects.select_related(
            "user__agence", "type_carte", "campagne", "traite_par"
        ),
        partenaire_courant(request),
    )


@role_required(Role.ADMIN)
@http_methods("GET", "HEAD")
def demandes_index(request):
    statut = request.GET.get("statut") or StatutDemande.EN_ATTENTE
    demandes = _demandes(request)
    compteurs = {s: demandes.filter(statut=s).count() for s in STATUTS}
    if statut in STATUTS:
        demandes = demandes.filter(statut=statut)
    ordre = "created_at" if statut == StatutDemande.EN_ATTENTE else "-traite_le"
    return render(
        request,
        "Admin/Demandes/Index",
        {
            "demandes": paginer(
                request, demandes.order_by(ordre, "id"), 10,
                lambda d: _formater(d, avec_comparaison=True),
            ),
            "filters": tableau({"statut": statut}),
            "compteurs": compteurs,
            "statuts": [{"id": k, "nom": v} for k, v in STATUTS.items()],
        },
    )


def _demande_en_attente(request, demande_id):
    demande = get_object_or_404(_demandes(request), pk=demande_id)
    if demande.statut != StatutDemande.EN_ATTENTE:
        deposer_flash(request, error="Cette demande a déjà été traitée.")
        return None
    return demande


@role_required(Role.ADMIN)
@http_methods("POST")
def demandes_valider(request, demande):
    demande = _demande_en_attente(request, demande)
    if demande is None:
        return redirect("/admin/demandes-clients")

    donnees = demande.donnees.get("vente", {})
    adhesion = demande.donnees.get("adhesion")
    try:
        with transaction.atomic():
            vente = services.enregistrer_vente(donnees, demande.user, adhesion, demande=demande)
            demande.statut = StatutDemande.ACCEPTEE
            demande.vente = vente
            demande.traite_par = request.user
            demande.traite_le = datetime.now().replace(microsecond=0)
            demande.commentaire_admin = (request.POST.get("commentaire") or "").strip() or None
            demande.save()
    except services.ErreurMetier as erreur:
        deposer_flash(request, error=f"Validation impossible : {erreur}")
        return redirect("/admin/demandes-clients")

    deposer_flash(
        request,
        success=f"Demande validée : la vente de {demande.nom_complet} est enregistrée "
        f"pour {_nom(demande.user)}.",
    )
    return redirect("/admin/demandes-clients")


@role_required(Role.ADMIN)
@http_methods("POST")
def demandes_refuser(request, demande):
    demande = _demande_en_attente(request, demande)
    if demande is None:
        return redirect("/admin/demandes-clients")

    commentaire = (request.POST.get("commentaire") or "").strip()
    demande.statut = StatutDemande.REFUSEE
    demande.traite_par = request.user
    demande.traite_le = datetime.now().replace(microsecond=0)
    demande.commentaire_admin = commentaire or None
    demande.save()
    deposer_flash(request, success=f"Demande refusée : aucune vente n'est enregistrée pour {demande.nom_complet}.")
    return redirect("/admin/demandes-clients")


@role_required(Role.COMMERCIAL)
@http_methods("GET", "HEAD")
def mes_demandes(request):
    en_cours = services.campagnes_en_cours_commercial(request.user)
    demandes = DemandeClientExistant.objects.filter(
        user=request.user, campagne_id__in=[c.id for c in en_cours]
    ).select_related(
        "user__agence", "type_carte", "campagne", "traite_par"
    )
    return render(
        request,
        "Commercial/Demandes/Index",
        {
            "demandes": paginer(
                request, demandes.order_by("-created_at", "-id"), 20,
                lambda d: _formater(d, avec_comparaison=False),
            ),
        },
    )


def nombre_en_attente(request):
    """Pour le badge du menu de l'administrateur."""
    return _demandes(request).filter(statut=StatutDemande.EN_ATTENTE).count()
