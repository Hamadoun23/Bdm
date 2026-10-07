"""
Écrans terrain : ventes, enrôlements, clients, contrat de prestation et
reporting téléphonique.

Portage de app/Http/Controllers/{Commercial,Clients,Api}/*.php.
"""

import re
from datetime import date, datetime

from django.core.exceptions import PermissionDenied
from django.db import transaction
from django.db.models import Count, Q, Value
from django.db.models.functions import Coalesce, Replace
from django.http import Http404, JsonResponse
from django.shortcuts import get_object_or_404, redirect
from inertia import render

from campagnes.models import (
    Campagne,
    CampagneAideVersement,
    ContratPrestationReponse,
    StatutReponseContrat,
    TypeCampagne,
)
from campagnes.articles_defaut import remuneration_dans_articles
from campagnes.services import totaux_telephonique
from core.decorators import http_methods, role_required
from core.middleware import deposer_flash, retour_avec_erreurs
from core.models import Agence, Role, TypeCarte, User
from core.pagination import paginer
from core.partenaires import (
    filtrer_agences,
    filtrer_campagnes,
    filtrer_saisies,
    filtrer_users,
    filtrer_types_cartes,
    partenaire_courant,
)
from core.php import nombre_format, tableau
from core.validation import ErreursValidation, Validateur

from . import doublons, services, telephones
from .models import (
    DELAI_MODIFICATION_COMMERCIAL_HEURES,
    Client,
    DemandeClientExistant,
    EnrolementClient,
    StatutCarte,
    StatutDemande,
    TelephoniqueRapport,
    TypePieceIdentite,
    Vente,
)


def _nom(user):
    if user is None:
        return None
    return f"{user.prenom} {user.name}".strip() if user.prenom else user.name


def _corps(request):
    """
    `CorpsJsonMiddleware` remplit déjà `request.POST` pour tout corps JSON,
    quelle que soit la méthode (POST/PUT/PATCH/DELETE) — un second passage ici
    via `QueryDict(request.body)` reparserait à tort le JSON brut comme une
    chaîne de requête et viderait tous les champs sur les mises à jour PUT.
    """
    return request.POST


# ---------------------------------------------------------------------------
# Ventes
# ---------------------------------------------------------------------------


@role_required(Role.ADMIN, Role.DIRECTION, Role.COMMERCIAL)
@http_methods("GET", "HEAD")
def ventes_index(request):
    user = request.user
    partenaire = partenaire_courant(request)
    ventes = filtrer_saisies(
        Vente.objects.select_related(
            "client", "agence", "user", "type_carte", "campagne"
        ),
        partenaire,
    )

    agence_id = None
    if user.is_commercial:
        ventes = ventes.filter(user_id=user.id)
        agence_id = int(user.agence_id) if user.agence_id else None
    # Admin et direction voient toutes les ventes du client courant, bornées au
    # périmètre de campagne.

    ventes = services.restreindre_aux_campagnes_vente(
        ventes, agence_id, partenaire.id if partenaire else None
    ).order_by("-created_at", "-id")

    def formater(v):
        return {
            "id": v.id,
            "date": v.created_at.strftime("%d/%m/%Y %H:%M"),
            "client_nom": f"{v.client.prenom} {v.client.nom}".strip(),
            "type_carte": v.type_carte.code if v.type_carte_id else "?",
            "commercial": v.user.name if v.user_id else "-",
            "agence": v.agence.nom if v.agence_id else "-",
            "peut_modifier_client": v.client.peut_etre_modifie_ou_supprime_par_commercial()
            if v.client_id
            else False,
            "peut_supprimer": v.peut_etre_supprimee_par_commercial(),
            "client_id": v.client_id,
        }

    return render(
        request,
        "Ventes/Index",
        {
            "libelleStatsCampagne": services.libelle_stats(
                agence_id, TypeCampagne.VENTE_CARTE,
                partenaire.id if partenaire else None,
            ),
            "canManage": bool(user.is_commercial),
            "canSeeCommercial": bool(user.is_admin or user.is_direction),
            "aDesAgences": partenaire is None or partenaire.a_des_agences,
            "ventes": paginer(request, ventes, 15, formater),
        },
    )


@role_required(Role.COMMERCIAL)
@http_methods("GET", "HEAD")
def ventes_create(request):
    Campagne.sync_statuts()
    user = request.user
    ouvertes = services.campagnes_ouvertes_pour(user, TypeCampagne.VENTE_CARTE)

    # La demande d'adhésion s'affiche dès que le client de GDA l'exige.
    partenaire = partenaire_courant(request)
    return render(
        request,
        "Ventes/Create",
        {
            "typesCartes": [
                {"id": t.id, "code": t.code}
                for t in filtrer_types_cartes(
                    TypeCarte.objects.filter(actif=True), partenaire
                ).order_by("code")
            ],
            "campagnesOuvertes": [
                {"id": c.id, "nom": c.nom, "date_fin": c.date_fin.strftime("%d/%m/%Y")}
                for c in ouvertes
            ],
            "peutVendre": bool(ouvertes),
            "contratAccepte": any(
                c.commercial_a_accepte_contrat(user.id) for c in ouvertes
            ),
            "ficheAdhesion": bool(partenaire and partenaire.fiche_adhesion),
            "clientNom": partenaire.nom if partenaire else None,
            "typesPiece": [
                {"valeur": v, "libelle": l} for v, l in TypePieceIdentite.choices
            ],
        },
    )


@role_required(Role.COMMERCIAL)
@http_methods("POST", "DELETE")
def ventes_destroy(request, vente):
    vente = get_object_or_404(Vente, pk=vente)
    if int(vente.user_id) != int(request.user.id):
        raise PermissionDenied

    if not vente.peut_etre_supprimee_par_commercial():
        deposer_flash(
            request,
            error=f"Suppression impossible : plus de {DELAI_MODIFICATION_COMMERCIAL_HEURES} h "
            "se sont écoulées depuis l’enregistrement de cette vente.",
        )
        return redirect("/ventes")

    # La fiche client n'existe que pour porter la vente : les deux disparaissent ensemble.
    with transaction.atomic():
        client = Client.objects.filter(pk=vente.client_id).first()
        vente.delete()
        if client:
            _supprimer_piece_identite(client)
            client.delete()

    deposer_flash(request, success="Vente supprimée.")
    return redirect("/ventes")


def _supprimer_piece_identite(client):
    """Retire le justificatif d'identité du disque public, s'il existe."""
    if not client.carte_identite:
        return
    from django.conf import settings

    chemin = settings.MEDIA_ROOT / client.carte_identite
    if chemin.exists():
        chemin.unlink()


@role_required(Role.COMMERCIAL)
@http_methods("POST")
def api_vente_store(request):
    """Enregistrement d'une vente depuis le formulaire React (réponse JSON)."""
    user = request.user
    if not user.is_commercial or not (user.agence_id or user.partenaire_id):
        return JsonResponse(
            {
                "success": False,
                "message": "Accès non autorisé. Seuls les commerciaux peuvent enregistrer des ventes.",
            },
            status=403,
        )

    Campagne.sync_statuts()
    ids_ouvertes = [
        c.id for c in services.campagnes_ouvertes_pour(user, TypeCampagne.VENTE_CARTE)
    ]
    if not ids_ouvertes:
        return JsonResponse(
            {
                "success": False,
                "message": "Aucune campagne ouverte pour votre périmètre : "
                "enregistrement de vente impossible.",
            },
            status=400,
        )

    validateur = Validateur(request.POST)
    validateur.champ("prenom", "required|max:100")
    validateur.champ("nom", "required|max:100")
    validateur.champ("telephone", "required|max:25")
    validateur.champ("ville", "nullable|max:100")
    validateur.champ("quartier", "nullable|max:100")
    validateur.champ("type_carte_id", "required|integer")
    validateur.champ("campagne_id", "nullable|integer")
    validateur.existe(
        "type_carte_id",
        filtrer_types_cartes(
            TypeCarte.objects.filter(actif=True), partenaire_courant(request)
        ),
    )
    # La campagne n'est exigée que s'il y a une ambiguïté à lever.
    if len(ids_ouvertes) > 1 and not validateur.valeurs.get("campagne_id"):
        validateur.erreur("campagne_id", "Le champ campagne id est obligatoire.")
    if validateur.valeurs.get("campagne_id") and validateur.valeurs["campagne_id"] not in ids_ouvertes:
        validateur.erreur("campagne_id", "Le champ campagne id est invalide.")

    try:
        donnees = validateur.resultat()
    except ErreursValidation as erreur:
        return JsonResponse(
            {
                "success": False,
                "message": "Données invalides.",
                "errors": {champ: [message] for champ, message in erreur.erreurs.items()},
            },
            status=422,
        )

    erreur_tel = _controler_telephone(donnees, user, "vente")
    if erreur_tel:
        return _refus_telephone(erreur_tel)

    if len(ids_ouvertes) == 1:
        donnees["campagne_id"] = ids_ouvertes[0]

    # Client déjà enregistré : la vente passe par l'administrateur.
    existants = telephones.clients_existants(
        donnees["telephone"], donnees["prenom"], donnees["nom"], user
    )
    if existants and request.POST.get("demande_validation") != "1":
        return JsonResponse(_reponse_client_existant(existants, donnees), status=409)

    fichier = request.FILES.get("carte_identite")
    if fichier:
        donnees["carte_identite"] = _stocker_piece_identite(fichier)

    partenaire = partenaire_courant(request)
    adhesion = None
    if partenaire and partenaire.fiche_adhesion:
        try:
            adhesion = _valider_adhesion(request, donnees)
        except ErreursValidation as erreur:
            return JsonResponse(
                {
                    "success": False,
                    "message": "Données invalides.",
                    "errors": {
                        champ: [message]
                        for champ, message in erreur.erreurs.items()
                    },
                },
                status=422,
            )

    if existants:
        try:
            demande = _creer_demande(donnees, user, adhesion, existants, request.POST.get("motif"))
        except services.ErreurMetier as erreur:
            return JsonResponse({"success": False, "message": str(erreur)}, status=400)
        return JsonResponse(
            {
                "success": True,
                "demande": True,
                "message": "Ce client est déjà enregistré : votre vente a été envoyée à "
                "l’administrateur. Elle sera comptée dès qu’il l’aura validée.",
                "demande_id": demande.id,
            },
            status=202,
        )

    try:
        vente = services.enregistrer_vente(donnees, user, adhesion)
    except services.ErreurMetier as erreur:
        return JsonResponse({"success": False, "message": str(erreur)}, status=400)

    return JsonResponse(
        {
            "success": True,
            "message": "Vente enregistrée avec succès.",
            "vente": {"id": vente.id, "campagne_id": vente.campagne_id},
        },
        status=201,
    )


def _controler_telephone(donnees, user, nature, exclure_id=None):
    """
    Met le numéro au format international dans `donnees` et vérifie qu'il
    n'appartient pas déjà à quelqu'un d'autre. Renvoie le message de refus.
    """
    try:
        donnees["telephone"] = telephones.normaliser(donnees.get("telephone"))
    except telephones.NumeroInvalide as erreur:
        return str(erreur)
    return telephones.verifier(
        donnees["telephone"], donnees["prenom"], donnees["nom"], user,
        nature=nature, exclure_id=exclure_id,
    )


def _reponse_client_existant(existants, donnees):
    """Ce que le commercial doit savoir avant d'envoyer sa demande."""
    type_carte_id = int(donnees["type_carte_id"])
    meme_type = any(c.type_carte_id == type_carte_id for c in existants)
    return {
        "success": False,
        "client_existant": True,
        "meme_type_carte": meme_type,
        "message": (
            "Ce client a déjà cette carte. " if meme_type else "Ce client est déjà enregistré. "
        )
        + "La vente ne peut être enregistrée qu’avec l’accord de l’administrateur : "
        "indiquez le motif et envoyez la demande.",
        "existants": [
            {
                "nom_complet": f"{c.prenom} {c.nom}".strip(),
                "telephone": c.telephone,
                "type_carte": c.type_carte.code if c.type_carte_id else "?",
                "meme_type_carte": c.type_carte_id == type_carte_id,
                "commercial": _nom(c.user) or "—",
                "date": c.created_at.strftime("%d/%m/%Y %H:%M") if c.created_at else "—",
            }
            for c in existants
        ],
    }


def _creer_demande(donnees, user, adhesion, existants, motif):
    """Enregistre la demande de vente à un client existant, sans créer la vente."""
    _, _, campagne, _ = services.preparer_vente(donnees, user, adhesion)
    type_carte_id = int(donnees["type_carte_id"])
    deja = DemandeClientExistant.objects.filter(
        user=user, statut=StatutDemande.EN_ATTENTE, type_carte_id=type_carte_id,
        telephone=donnees["telephone"],
    ).first()
    if deja:
        raise services.ErreurMetier(
            f"Une demande pour ce client et cette carte est déjà en attente depuis le "
            f"{deja.created_at.strftime('%d/%m/%Y %H:%M')}."
        )

    def serialisable(valeurs):
        return {k: (v.isoformat() if hasattr(v, "isoformat") else v) for k, v in (valeurs or {}).items()}

    return DemandeClientExistant.objects.create(
        user=user,
        campagne=campagne,
        type_carte_id=type_carte_id,
        client_existant=existants[0],
        telephone=donnees["telephone"],
        prenom=donnees["prenom"],
        nom=donnees["nom"],
        donnees={"vente": serialisable(donnees), "adhesion": serialisable(adhesion) if adhesion else None},
        meme_type_carte=any(c.type_carte_id == type_carte_id for c in existants),
        motif=(motif or "").strip()[:2000] or None,
    )


def _refus_telephone(message, champ="telephone"):
    return JsonResponse(
        {"success": False, "message": message, "errors": {champ: [message]}},
        status=422,
    )


def _valider_adhesion(request, donnees):
    """
    Valide la demande d'adhésion carte prépayée et la ramène à un dictionnaire.

    Seuls le nom à imprimer sur la carte et la pièce d'identité sont exigés en
    plus de l'état civil : ce sont eux qui bloquent l'émission côté banque. Le
    reste est recopié tel quel de l'imprimé, et peut rester vide sur le terrain.
    """
    validateur = Validateur(request.POST)
    validateur.champ("date_naissance", "nullable|date")
    validateur.champ("lieu_naissance", "nullable|max:191")
    validateur.champ("nationalite", "nullable|max:100")
    validateur.champ("email", "nullable|email")
    validateur.champ("adresse", "nullable|max:255")
    validateur.champ("pays_residence", "nullable|max:100")
    validateur.champ("nom_sur_carte", "required|max:100")
    validateur.champ("piece_type", "required|in:cni,passeport,nina")
    validateur.champ("piece_numero", "required|max:100")
    validateur.champ("piece_delivree_le", "nullable|date")
    validateur.champ("piece_expire_le", "nullable|date")
    validateur.champ("piece_autorite", "nullable|max:191")
    validateur.champ("numero_compte_uba", "nullable|max:50")
    validateur.champ("profession", "nullable|max:191")
    validateur.champ("employeur", "nullable|max:191")

    valides = validateur.resultat()

    # L'état civil de la fiche est celui de la vente : une seule saisie, deux
    # enregistrements. Les divergences seraient impossibles à arbitrer ensuite.
    valides["nom"] = donnees["nom"]
    valides["prenoms"] = donnees["prenom"]
    valides["telephone"] = donnees.get("telephone")
    valides["ville"] = donnees.get("ville")
    valides["quartier"] = donnees.get("quartier")
    return valides


def _stocker_piece_identite(fichier):
    """Enregistre le justificatif et renvoie son chemin relatif au disque public."""
    from django.core.files.storage import FileSystemStorage
    from django.conf import settings

    stockage = FileSystemStorage(location=settings.MEDIA_ROOT / "cartes-identite")
    nom = stockage.save(fichier.name, fichier)
    return f"cartes-identite/{nom}"


# ---------------------------------------------------------------------------
# Enrôlements
# ---------------------------------------------------------------------------


@role_required(Role.ADMIN, Role.DIRECTION, Role.COMMERCIAL)
@http_methods("GET", "HEAD")
def enrolements_index(request):
    user = request.user
    partenaire = partenaire_courant(request)
    enrolements = filtrer_saisies(
        EnrolementClient.objects.select_related("user", "agence", "campagne"),
        partenaire,
    )
    if user.is_commercial:
        enrolements = enrolements.filter(user_id=user.id)
    enrolements = enrolements.order_by("-created_at", "-id")

    def formater(e):
        return {
            "id": e.id,
            "date": e.created_at.strftime("%d/%m/%Y %H:%M"),
            "client_nom": f"{e.prenom} {e.nom}".strip(),
            "numero_compte": e.numero_compte,
            "telephone": e.telephone,
            "adresse": e.adresse,
            "commercial": e.user.name if e.user_id else "-",
            "agence": e.agence.nom if e.agence_id else "-",
            "peut_supprimer": e.peut_etre_modifie_ou_supprime_par_commercial(),
        }

    return render(
        request,
        "Enrolements/Index",
        {
            "canManage": bool(user.is_commercial),
            "canSeeCommercial": bool(user.is_admin or user.is_direction),
            "aDesAgences": partenaire is None or partenaire.a_des_agences,
            "enrolements": paginer(request, enrolements, 15, formater),
        },
    )


@role_required(Role.COMMERCIAL)
@http_methods("GET", "HEAD")
def enrolements_create(request):
    Campagne.sync_statuts()
    user = request.user
    ouvertes = services.campagnes_ouvertes_pour(user, TypeCampagne.ENROLEMENT_APP)

    return render(
        request,
        "Enrolements/Create",
        {
            "campagnesOuvertes": [
                {"id": c.id, "nom": c.nom, "date_fin": c.date_fin.strftime("%d/%m/%Y")}
                for c in ouvertes
            ],
            "peutEnroler": bool(ouvertes),
            "contratAccepte": any(
                c.commercial_a_accepte_contrat(user.id) for c in ouvertes
            ),
        },
    )


@role_required(Role.COMMERCIAL)
@http_methods("POST", "DELETE")
def enrolements_destroy(request, enrolement):
    enrolement = get_object_or_404(EnrolementClient, pk=enrolement)
    if int(enrolement.user_id) != int(request.user.id):
        raise PermissionDenied

    if not enrolement.peut_etre_modifie_ou_supprime_par_commercial():
        deposer_flash(
            request,
            error=f"Suppression impossible : plus de {DELAI_MODIFICATION_COMMERCIAL_HEURES} h "
            "se sont écoulées depuis l’enregistrement.",
        )
        return redirect("/enrolements")

    enrolement.delete()
    deposer_flash(request, success="Enrôlement supprimé.")
    return redirect("/enrolements")


@role_required(Role.COMMERCIAL)
@http_methods("POST")
def api_enrolement_store(request):
    user = request.user
    if not user.is_commercial or not (user.agence_id or user.partenaire_id):
        return JsonResponse(
            {
                "success": False,
                "message": "Accès non autorisé. Seuls les commerciaux peuvent enregistrer des enrôlements.",
            },
            status=403,
        )

    Campagne.sync_statuts()
    ids_ouvertes = [
        c.id for c in services.campagnes_ouvertes_pour(user, TypeCampagne.ENROLEMENT_APP)
    ]
    if not ids_ouvertes:
        return JsonResponse(
            {
                "success": False,
                "message": "Aucune campagne d’enrôlement ouverte pour votre agence.",
            },
            status=400,
        )

    validateur = Validateur(request.POST)
    validateur.champ("nom", "required|max:255")
    validateur.champ("prenom", "required|max:255")
    validateur.champ("numero_compte", "required|max:50")
    validateur.champ("telephone", "required|max:25")
    validateur.champ("adresse", "nullable|max:255")
    validateur.champ("campagne_id", "nullable|integer")
    if len(ids_ouvertes) > 1 and not validateur.valeurs.get("campagne_id"):
        validateur.erreur("campagne_id", "Le champ campagne id est obligatoire.")

    try:
        donnees = validateur.resultat()
    except ErreursValidation as erreur:
        return JsonResponse(
            {
                "success": False,
                "message": "Données invalides.",
                "errors": {champ: [message] for champ, message in erreur.erreurs.items()},
            },
            status=422,
        )

    erreur_tel = _controler_telephone(donnees, user, "enrolement")
    if erreur_tel:
        return _refus_telephone(erreur_tel)
    erreur_compte = telephones.verifier_compte(donnees["numero_compte"])
    if erreur_compte:
        return _refus_telephone(erreur_compte, champ="numero_compte")

    if len(ids_ouvertes) == 1:
        donnees["campagne_id"] = ids_ouvertes[0]

    try:
        enrolement = services.enregistrer_enrolement(donnees, user)
    except services.ErreurMetier as erreur:
        return JsonResponse({"success": False, "message": str(erreur)}, status=400)

    return JsonResponse(
        {
            "success": True,
            "message": "Enrôlement enregistré avec succès.",
            "enrolement": {"id": enrolement.id, "campagne_id": enrolement.campagne_id},
        },
        status=201,
    )


# ---------------------------------------------------------------------------
# Clients — consultation admin / direction
# ---------------------------------------------------------------------------


@role_required(Role.ADMIN, Role.DIRECTION)
@http_methods("GET", "HEAD")
def clients_index(request):
    partenaire = partenaire_courant(request)
    base = filtrer_saisies(Client.objects.all(), partenaire)
    f = filtres_clients(request)
    ids_filtres = set(
        appliquer_filtres_clients(base, f).values_list("id", flat=True)
    )
    critere = critere_doublons(f)

    props = {
        "filters": tableau({cle: v for cle, v in f.items() if v}),
        "choix": _choix_filtres_clients(base, partenaire),
        "tableauDeBord": _tableau_de_bord_clients(ids_filtres),
    }

    if critere:
        tous_groupes, groupes = analyser_doublons(base, ids_filtres, critere, f)
        noms = noms_commerciaux({x.user_id for g in tous_groupes for x in g.fiches})
        totaux = totaux_par_commercial(base)
        resaisies = [x for g in groupes for x in g.fiches if x.cible]
        classement = doublons.classement_commerciaux(resaisies, totaux)
        for ligne in classement:
            ligne["commercial"] = noms.get(ligne["user_id"], "—")
        props["doublons"] = {
            "critere": critere,
            "stats": doublons.statistiques(groupes),
            "classement": classement[:20],
            "audit": _audit_commercial(tous_groupes, f, noms, totaux),
            "avecNom": noms.get(int(f["avec"])) if f["avec"].isdigit() else None,
            "groupes": paginer(request, groupes, 15, _formateur_groupes(ids_filtres, noms)),
        }
    else:
        par_numero = {}
        for pk, tel in base.values_list("id", "telephone"):
            cle = doublons.cle_numero(tel)
            if cle:
                par_numero[cle] = par_numero.get(cle, 0) + 1
        clients = (
            Client.objects.filter(pk__in=ids_filtres)
            .select_related("user__agence", "type_carte")
            .prefetch_related("ventes__agence", "ventes__campagne")
            .order_by("-created_at", "-id")
        )

        def formater(c):
            ligne = ligne_client(c)
            ligne["fiches_meme_numero"] = par_numero.get(doublons.cle_numero(c.telephone), 0)
            return ligne

        props["clients"] = paginer(request, clients, 20, formater)

    return render(request, "Clients/Index", props)


FILTRES_CLIENTS = (
    "q", "type_carte_id", "user_id", "agence_id", "campagne_id", "statut",
    "ville", "du", "au", "doublons", "cas", "delai", "campagne_resaisie",
    "resaisie_du", "resaisie_au", "tri", "avec",
)


def filtres_clients(request):
    return {cle: (request.GET.get(cle) or "").strip() for cle in FILTRES_CLIENTS}


def critere_doublons(f):
    # « 1 » : ancienne case « Numéros en double », conservée pour les liens existants.
    if f["doublons"] == "1":
        return doublons.CRITERE_NUMERO
    return f["doublons"] if f["doublons"] in doublons.CRITERES else None


def appliquer_filtres_clients(clients, f):
    if f["q"]:
        # Les mots portent sur le nom, les chiffres sur le téléphone, quelle que
        # soit la façon dont il a été saisi (espaces, tirets, points).
        for mot in f["q"].split():
            if not mot.isdigit():
                clients = clients.filter(
                    Q(prenom__icontains=mot)
                    | Q(nom__icontains=mot)
                    | Q(quartier__icontains=mot)
                    | Q(carte_identite__icontains=mot)
                )
        chiffres = "".join(m for m in f["q"].split() if m.isdigit())
        if chiffres:
            clients = clients.annotate(
                tel_chiffres=Replace(
                    Replace(Replace(Coalesce("telephone", Value("")), Value(" "), Value("")),
                            Value("-"), Value("")),
                    Value("."), Value(""),
                )
            ).filter(tel_chiffres__contains=chiffres)
    if f["type_carte_id"].isdigit():
        clients = clients.filter(type_carte_id=int(f["type_carte_id"]))
    if f["user_id"].isdigit():
        clients = clients.filter(user_id=int(f["user_id"]))
    if f["agence_id"] == "aucune":
        clients = clients.filter(ventes__agence_id__isnull=True)
    elif f["agence_id"].isdigit():
        clients = clients.filter(ventes__agence_id=int(f["agence_id"]))
    if f["campagne_id"] == "aucune":
        clients = clients.filter(ventes__campagne_id__isnull=True)
    elif f["campagne_id"].isdigit():
        clients = clients.filter(ventes__campagne_id=int(f["campagne_id"]))
    if f["statut"] in StatutCarte.values:
        clients = clients.filter(statut_carte=f["statut"])
    if f["ville"] == "aucune":
        clients = clients.filter(Q(ville__isnull=True) | Q(ville=""))
    elif f["ville"]:
        clients = clients.filter(ville__iexact=f["ville"])
    du, au = _date_iso(f["du"]), _date_iso(f["au"])
    if du:
        clients = clients.filter(created_at__date__gte=du)
    if au:
        clients = clients.filter(created_at__date__lte=au)
    return clients.distinct()


def analyser_doublons(base, ids_filtres, critere, f):
    """
    Renvoie (tous les groupes, groupes retenus par les filtres d'audit).

    L'analyse porte sur toutes les fiches du partenaire : filtrer sur un
    commercial doit montrer qu'il a ressaisi le client d'un autre, même si la
    fiche de l'autre n'entre pas dans le filtre. Chaque re-saisie (fiche de
    rang 2 ou plus) est examinée ; un groupe est retenu dès qu'une re-saisie
    correspond au cas, au délai, à la campagne et à la période demandés. Ces
    re-saisies sont marquées `cible` pour être mises en évidence.
    """
    campagnes = dict(
        Vente.objects.filter(client_id__in=base.values("id")).values_list(
            "client_id", "campagne_id"
        )
    )
    fiches = [
        doublons.Fiche(i, p, n, t, u, c, campagne_id=campagnes.get(i))
        for i, p, n, t, u, c in base.values_list(
            "id", "prenom", "nom", "telephone", "user_id", "created_at"
        )
    ]
    tous = doublons.analyser(fiches, critere)
    du, au = _date_iso(f["resaisie_du"]), _date_iso(f["resaisie_au"])

    def correspond(x, premiere):
        cas = f["cas"]
        if cas == doublons.CAS_AUTRE:
            ok = x.id in ids_filtres and x.statut == doublons.RESAISIE_AUTRE
        elif cas == doublons.CAS_MEME:
            ok = x.id in ids_filtres and x.statut == doublons.RESAISIE_MEME
        elif cas == doublons.CAS_VICTIME:
            ok = premiere.id in ids_filtres and x.user_id != premiere.user_id
        else:
            ok = x.id in ids_filtres or premiere.id in ids_filtres
        if not ok:
            return False
        # « avec » : l'autre commercial impliqué, qu'il ait saisi le client en
        # premier ou qu'il l'ait ressaisi.
        if f["avec"].isdigit() and int(f["avec"]) not in (x.user_id, premiere.user_id):
            return False
        if f["delai"] in doublons.DELAIS and doublons.tranche_delai(x.delai) != f["delai"]:
            return False
        if f["campagne_resaisie"] == "meme" and x.campagne_id != premiere.campagne_id:
            return False
        if f["campagne_resaisie"] == "autre" and x.campagne_id == premiere.campagne_id:
            return False
        jour = x.created_at.date() if x.created_at else None
        if du and (not jour or jour < du):
            return False
        if au and (not jour or jour > au):
            return False
        return True

    retenus = []
    for g in tous:
        premiere = g.fiches[0]
        for x in g.fiches[1:]:
            x.cible = correspond(x, premiere)
        if any(x.cible for x in g.fiches):
            retenus.append(g)

    def derniere(g):
        return max(x.created_at or datetime.min for x in g.fiches if x.cible)

    tri = f["tri"]
    if tri == "recent":
        retenus.sort(key=derniere, reverse=True)
    elif tri == "ancien":
        retenus.sort(key=derniere)
    elif tri == "delai":
        retenus.sort(key=lambda g: -max(x.delai or 0 for x in g.fiches if x.cible))
    for i, g in enumerate(retenus, 1):
        g.numero = i
    return tous, retenus


def _audit_commercial(groupes, f, noms, totaux):
    """Bilan du commercial filtré, ou None si aucun commercial n'est choisi."""
    if not f["user_id"].isdigit():
        return None
    user_id = int(f["user_id"])
    a = doublons.audit_commercial(groupes, user_id)
    campagnes = dict(Campagne.objects.values_list("id", "nom"))
    resaisies = a["resaisies_autre"] + a["resaisies_meme"]
    total = totaux.get(user_id, 0)
    ordre_delais = list(doublons.DELAIS)
    return {
        "commercial": noms.get(user_id) or _nom(User.objects.filter(pk=user_id).first()) or "—",
        "total_fiches": total,
        "resaisies_autre": a["resaisies_autre"],
        "resaisies_meme": a["resaisies_meme"],
        "pourcentage": round(100 * resaisies / total, 1) if total else 0,
        "clients_ressaisis_par_autres": a["clients_ressaisis_par_autres"],
        "pris_a": [{"id": u, "nom": noms.get(u, "—"), "total": n} for u, n in a["pris_a"]],
        "pris_par": [{"id": u, "nom": noms.get(u, "—"), "total": n} for u, n in a["pris_par"]],
        "par_campagne": [
            {"id": c or "aucune", "nom": campagnes.get(c, "Sans campagne"), "total": n}
            for c, n in a["par_campagne"]
        ],
        "par_delai": [
            {"id": d, "nom": doublons.DELAIS[d][0], "total": n}
            for d, n in sorted(
                (x for x in a["par_delai"] if x[0] in doublons.DELAIS),
                key=lambda x: ordre_delais.index(x[0]),
            )
        ],
        "par_mois": [{"id": m, "nom": _mois_fr(m), "total": n} for m, n in a["par_mois"] if m],
        "premiere": a["premiere"].strftime("%d/%m/%Y") if a["premiere"] else None,
        "derniere": a["derniere"].strftime("%d/%m/%Y") if a["derniere"] else None,
    }


MOIS = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août",
        "septembre", "octobre", "novembre", "décembre"]


def _mois_fr(cle):
    annee, mois = cle.split("-")
    return f"{MOIS[int(mois) - 1].capitalize()} {annee}"


def noms_commerciaux(user_ids):
    return {u.id: _nom(u) or u.name for u in User.objects.filter(pk__in=user_ids)}


def totaux_par_commercial(clients):
    return dict(
        clients.values_list("user_id")
        .annotate(n=Count("id"))
        .order_by()
    )


def ligne_client(c):
    vente = next(iter(c.ventes.all()), None)
    agence = vente.agence if vente and vente.agence_id else (c.user.agence if c.user_id else None)
    return {
        "id": c.id,
        "nom_complet": f"{c.prenom} {c.nom}".strip(),
        "telephone": c.telephone,
        "ville": c.ville,
        "type_carte": c.type_carte.code if c.type_carte_id else "?",
        "commercial": c.user.name if c.user_id else "—",
        "agence": agence.nom if agence else None,
        "campagne": vente.campagne.nom if vente and vente.campagne_id else None,
        "statut_carte": c.statut_carte,
        "date": c.created_at.strftime("%d/%m/%Y") if c.created_at else None,
    }


def lignes_groupe(g, objets, ids_filtres, noms):
    """Les fiches d'un groupe de doublons, prêtes à comparer."""
    premiere = g.fiches[0]
    lignes = []
    for x in g.fiches:
        ligne = ligne_client(objets[x.id])
        ligne.update({
            "commercial": noms.get(x.user_id, ligne["commercial"]),
            "heure": x.created_at.strftime("%d/%m/%Y %H:%M") if x.created_at else "—",
            "rang": x.rang,
            "statut_saisie": x.statut,
            "jours_apres": x.delai,
            "dans_filtre": x.id in ids_filtres,
            "cible": x.cible,
            "meme_campagne": x.campagne_id == premiere.campagne_id if x.rang > 1 else None,
        })
        lignes.append(ligne)
    return lignes


def charger_clients(ids):
    return {
        c.id: c
        for c in Client.objects.filter(pk__in=ids)
        .select_related("user__agence", "type_carte")
        .prefetch_related("ventes__agence", "ventes__campagne")
    }


def _formateur_groupes(ids_filtres, noms):
    def formater(g):
        objets = charger_clients([x.id for x in g.fiches])
        return {
            "numero": g.numero,
            "cle": g.cle,
            "nb_fiches": len(g.fiches),
            "nb_commerciaux": len(g.commerciaux),
            "premiere_par": noms.get(g.fiches[0].user_id, "—"),
            "fiches": lignes_groupe(g, objets, ids_filtres, noms),
        }

    return formater


def _tableau_de_bord_clients(ids):
    """Répartition des clients filtrés : d'où ils viennent."""
    qs = Client.objects.filter(pk__in=ids)

    def repartition(champ_id, champ_nom, vide):
        return [
            {"id": i if i is not None else "aucune", "nom": n or vide, "total": t}
            for i, n, t in qs.values_list(champ_id, champ_nom)
            .annotate(t=Count("id", distinct=True))
            .order_by("-t")
        ]

    villes = {}
    for v in qs.values_list("ville", flat=True):
        cle = (v or "").strip() or None
        villes[cle] = villes.get(cle, 0) + 1
    return {
        "total": len(ids),
        "commerciaux": qs.values("user_id").distinct().count(),
        "parCampagne": repartition("ventes__campagne_id", "ventes__campagne__nom", "Sans campagne"),
        "parAgence": repartition("ventes__agence_id", "ventes__agence__nom", "Sans agence"),
        "parType": repartition("type_carte_id", "type_carte__code", "?"),
        "parCommercial": [
            {"id": i, "nom": f"{n or ''} {p or ''}".strip(), "total": t}
            for i, n, p, t in qs.values_list("user_id", "user__name", "user__prenom")
            .annotate(t=Count("id"))
            .order_by("-t")[:15]
        ],
        "parVille": [
            {"id": v if v else "aucune", "nom": v or "Ville non renseignée", "total": t}
            for v, t in sorted(villes.items(), key=lambda x: -x[1])[:15]
        ],
    }


def _choix_filtres_clients(base, partenaire):
    commerciaux = filtrer_users(
        User.objects.filter(pk__in=base.values("user_id")), partenaire
    ).order_by("name", "prenom")
    return {
        "types": [
            {"id": t.id, "nom": t.code}
            for t in filtrer_types_cartes(TypeCarte.objects, partenaire).order_by("code")
        ],
        "commerciaux": [
            {"id": u.id, "nom": f"{u.name or ''} {u.prenom or ''}".strip()}
            for u in commerciaux
        ],
        "agences": [
            {"id": a.id, "nom": a.nom}
            for a in filtrer_agences(Agence.objects, partenaire).order_by("nom")
        ],
        "campagnes": [
            {"id": c.id, "nom": c.nom}
            for c in filtrer_campagnes(Campagne.objects, partenaire).order_by("-date_debut")
        ],
        "villes": sorted(
            {v.strip() for v in base.values_list("ville", flat=True) if v and v.strip()},
            key=str.casefold,
        ),
        "statuts": [{"id": v, "nom": l} for v, l in StatutCarte.choices],
        "criteres": [{"id": k, "nom": v} for k, v in doublons.CRITERES.items()],
        "cas": [{"id": k, "nom": v} for k, v in doublons.CAS.items()],
        "delais": [{"id": k, "nom": v[0]} for k, v in doublons.DELAIS.items()],
        "campagnesResaisie": [{"id": k, "nom": v} for k, v in doublons.CAMPAGNES_RESAISIE.items()],
        "tris": [{"id": k, "nom": v} for k, v in doublons.TRIS.items()],
    }


_cle_numero = doublons.cle_numero


def _date_iso(valeur):
    try:
        return datetime.strptime(valeur, "%Y-%m-%d").date()
    except (TypeError, ValueError):
        return None


@role_required(Role.ADMIN, Role.DIRECTION)
@http_methods("GET", "HEAD")
def clients_show(request, client):
    client = get_object_or_404(
        filtrer_saisies(
            Client.objects.select_related("user__agence", "type_carte"),
            partenaire_courant(request),
        ),
        pk=client,
    )
    ventes = client.ventes.select_related("agence", "type_carte", "user").all()
    partenaire = partenaire_courant(request)

    # Toutes les autres saisies portant le même numéro — ventes et
    # enrôlements — pour repérer un client enregistré plusieurs fois.
    meme_numero = []
    cle = _cle_numero(client.telephone)
    if cle:
        autres_clients = filtrer_saisies(
            Client.objects.select_related("user", "type_carte")
            .prefetch_related("ventes__agence", "ventes__campagne")
            .filter(telephone__contains=cle[-4:])
            .exclude(pk=client.pk),
            partenaire,
        )
        for c in autres_clients:
            if _cle_numero(c.telephone) != cle:
                continue
            vente = next(iter(c.ventes.all()), None)
            meme_numero.append({
                "nature": "vente",
                "id": c.id,
                "nom_complet": f"{c.prenom} {c.nom}".strip(),
                "telephone": c.telephone,
                "detail": c.type_carte.code if c.type_carte_id else "?",
                "commercial": _nom(c.user) or "—",
                "meme_commercial": c.user_id == client.user_id,
                "agence": vente.agence.nom if vente and vente.agence_id else None,
                "campagne": vente.campagne.nom if vente and vente.campagne_id else None,
                "horodatage": c.created_at,
            })
        enrolements = filtrer_saisies(
            EnrolementClient.objects.select_related("user", "agence", "campagne")
            .filter(telephone__contains=cle[-4:]),
            partenaire,
        )
        for e in enrolements:
            if _cle_numero(e.telephone) != cle:
                continue
            meme_numero.append({
                "nature": "enrolement",
                "id": e.id,
                "nom_complet": e.nom_complet,
                "telephone": e.telephone,
                "detail": e.numero_compte or "—",
                "commercial": _nom(e.user) or "—",
                "meme_commercial": e.user_id == client.user_id,
                "agence": e.agence.nom if e.agence_id else None,
                "campagne": e.campagne.nom if e.campagne_id else None,
                "horodatage": e.created_at,
            })
        meme_numero.sort(key=lambda x: x["horodatage"] or datetime.min)
        for x in meme_numero:
            h = x.pop("horodatage")
            x["avant"] = bool(h and client.created_at and h < client.created_at)
            x["date"] = h.strftime("%d/%m/%Y %H:%M") if h else "—"

    return render(
        request,
        "Clients/Show",
        {
            "client": {
                "id": client.id,
                "nom_complet": f"{client.prenom} {client.nom}".strip(),
                "telephone": client.telephone,
                "ville": client.ville,
                "quartier": client.quartier,
                "type_carte": client.type_carte.code if client.type_carte_id else "?",
                "statut_carte": client.statut_carte,
                "commercial": client.user.name if client.user_id else "—",
                "agence": client.user.agence.nom
                if client.user_id and client.user.agence_id
                else None,
                "created_at": client.created_at.strftime("%d/%m/%Y %H:%M"),
                "carte_identite_url": request.build_absolute_uri(
                    "/storage/" + client.carte_identite
                )
                if client.carte_identite
                else None,
                "ventes": [
                    {
                        "id": v.id,
                        "date": v.created_at.strftime("%d/%m/%Y %H:%M"),
                        "type_carte": v.type_carte.code if v.type_carte_id else "?",
                        "commercial": v.user.name if v.user_id else "—",
                        "agence": v.agence.nom if v.agence_id else "—",
                        "statut_activation": v.statut_activation,
                    }
                    for v in ventes
                ],
                "meme_numero": meme_numero,
            }
        },
    )


# ---------------------------------------------------------------------------
# Fiche client côté commercial (correction dans le délai de 48 h)
# ---------------------------------------------------------------------------


def _client_du_commercial(request, client_id):
    client = get_object_or_404(Client.objects.select_related("type_carte"), pk=client_id)
    if not request.user.is_commercial or int(client.user_id) != int(request.user.id):
        raise PermissionDenied("Vous ne pouvez modifier que vos propres clients.")
    return client


@role_required(Role.COMMERCIAL)
@http_methods("GET", "HEAD")
def commercial_client_edit(request, client):
    client = _client_du_commercial(request, client)
    modifiable = client.peut_etre_modifie_ou_supprime_par_commercial()

    return render(
        request,
        "Commercial/Clients/Edit",
        {
            "client": {
                "id": client.id,
                "prenom": client.prenom,
                "nom": client.nom,
                "telephone": client.telephone,
                "ville": client.ville,
                "quartier": client.quartier,
                "carte_identite_url": request.build_absolute_uri(
                    "/storage/" + client.carte_identite
                )
                if client.carte_identite
                else None,
                "type_carte_code": client.type_carte.code if client.type_carte_id else None,
                "verrouille": not modifiable,
                "peut_supprimer": modifiable,
            },
            "delaiHeures": DELAI_MODIFICATION_COMMERCIAL_HEURES,
        },
    )


@role_required(Role.COMMERCIAL)
@http_methods("POST", "PUT", "PATCH")
def commercial_client_update(request, client):
    client = _client_du_commercial(request, client)

    if not client.peut_etre_modifie_ou_supprime_par_commercial():
        deposer_flash(
            request,
            error=f"La modification n’est plus possible : plus de "
            f"{DELAI_MODIFICATION_COMMERCIAL_HEURES} h se sont écoulées depuis "
            "l’enregistrement du client.",
        )
        return redirect(f"/mes-clients/{client.id}/modifier")

    source = _corps(request)
    validateur = Validateur(source)
    validateur.champ("prenom", "required|max:100")
    validateur.champ("nom", "required|max:100")
    validateur.champ("telephone", "required|max:25")
    validateur.champ("ville", "nullable|max:100")
    validateur.champ("quartier", "nullable|max:100")
    try:
        donnees = validateur.resultat()
    except ErreursValidation as erreur:
        return retour_avec_erreurs(request, erreur.erreurs)

    erreur_tel = _controler_telephone(donnees, request.user, "vente", exclure_id=client.id)
    if erreur_tel:
        return retour_avec_erreurs(request, {"telephone": erreur_tel})

    for champ in ("prenom", "nom", "telephone", "ville", "quartier"):
        setattr(client, champ, donnees[champ])

    fichier = request.FILES.get("carte_identite")
    if fichier:
        _supprimer_piece_identite(client)
        client.carte_identite = _stocker_piece_identite(fichier)

    client.save()
    deposer_flash(request, success="Informations client mises à jour.")
    return redirect("/ventes")


@role_required(Role.COMMERCIAL)
@http_methods("POST", "DELETE")
def commercial_client_destroy(request, client):
    client = _client_du_commercial(request, client)

    if not client.peut_etre_modifie_ou_supprime_par_commercial():
        deposer_flash(
            request,
            error=f"La suppression n’est plus possible : plus de "
            f"{DELAI_MODIFICATION_COMMERCIAL_HEURES} heures se sont écoulées depuis "
            "l’enregistrement du client.",
        )
        return redirect(f"/mes-clients/{client.id}/modifier")

    _supprimer_piece_identite(client)
    client.delete()
    deposer_flash(
        request,
        success="Fiche client supprimée (ventes associées supprimées également).",
    )
    return redirect("/ventes")


# ---------------------------------------------------------------------------
# Contrat de prestation
# ---------------------------------------------------------------------------


def _campagne_du_commercial(user):
    """
    Campagne pour laquelle ce commercial voit et signe son contrat.

    D'abord la campagne en cours où il est engagé ; à défaut, la prochaine
    campagne programmée où il est engagé et dont le contrat est déjà publié —
    pour qu'il puisse le consulter et le signer avant le démarrage, sans
    attendre que la campagne passe « en cours ».
    """
    Campagne.sync_statuts()
    for campagne in Campagne.actives_pour_commercial(user):
        if campagne.est_engage_commercial(user.id):
            return campagne
    a_venir = Campagne.programmees_pour_commercial(user)
    return a_venir[0] if a_venir else None


@role_required(Role.COMMERCIAL, Role.COMMERCIAL_TELEPHONIQUE)
@http_methods("GET", "HEAD")
def contrat_show(request):
    user = request.user
    campagne = _campagne_du_commercial(user)

    if not campagne or not campagne.user_est_signataire_contrat(user):
        return render(request, "Commercial/Contrat/NoCampagne", {})

    reponse, _ = ContratPrestationReponse.objects.get_or_create(
        campagne_id=campagne.id,
        user_id=user.id,
        defaults={"statut": StatutReponseContrat.EN_ATTENTE},
    )

    verrou = bool(campagne.contrat_publie_at) and campagne.contrat_delai_expire()
    peut_repondre = (
        bool(campagne.contrat_publie_at)
        and not verrou
        and reponse.statut == StatutReponseContrat.EN_ATTENTE
    )

    contexte = services.donnees_contrat(campagne)
    versements = campagne.aide_versements.filter(user_id=user.id).order_by("-semaine_debut")
    # Le commercial peut répondre à tout moment pendant la campagne : la seule
    # échéance est sa date de fin (cf. Campagne.contrat_delai_expire).
    echeance = campagne.date_fin if campagne.contrat_publie_at else None

    return render(
        request,
        "Commercial/Contrat/Show",
        {
            "campagne": {
                "nom": campagne.nom,
                "date_debut": campagne.date_debut.strftime("%d/%m/%Y"),
                "date_fin": campagne.date_fin.strftime("%d/%m/%Y"),
                "contrat_publie_at": bool(campagne.contrat_publie_at),
                "aide_hebdo_active": campagne.aide_hebdo_active,
                # La campagne n'a pas encore démarré : le commercial peut
                # signer par avance (cf. _campagne_du_commercial).
                "a_venir": campagne.date_debut > date.today(),
            },
            "user": {
                "adresse_contrat": user.adresse_contrat,
                "piece_identite_ref": user.piece_identite_ref,
            },
            "reponse": {
                "statut": reponse.statut,
                "repondu_at": reponse.repondu_at.strftime("%d/%m/%Y %H:%M")
                if reponse.repondu_at
                else None,
            },
            "verrou5j": bool(verrou),
            "peutRepondre": bool(peut_repondre),
            "echeance": echeance.strftime("%d/%m/%Y") if echeance else None,
            "document": {
                # Le contrat UBA énonce la rémunération dans son article 4 ;
                # celui de la BDM la laisse au bloc calculé plus bas. Afficher
                # les deux la ferait dire deux fois, au risque de diverger.
                "remuneration_dans_articles": remuneration_dans_articles(
                    campagne.partenaire.contrat_modele
                    if campagne.partenaire_id
                    else None
                ),
                "client_nom": campagne.partenaire.nom if campagne.partenaire_id else None,
                "representant_nom": campagne.contrat_representant_nom,
                "nom_presta": _nom(user),
                "contact_presta": user.telephone or "—",
                "adresse": user.adresse_contrat or "………………………",
                "piece_id": user.piece_identite_ref or "………………………",
                "lundi_effectif": contexte["lundi_effectif"].strftime("%d/%m/%Y"),
                "date_fin": campagne.date_fin.strftime("%d/%m/%Y"),
                "nom_campagne": campagne.nom,
                "articles": [
                    {"titre": a.titre, "contenu": a.contenu}
                    for a in campagne.contrat_articles.order_by("sort_order")
                ],
                "emolument_forfait": nombre_format(campagne.contrat_emolument_forfait),
                "forfait_communication": nombre_format(
                    campagne.contrat_forfait_communication
                ),
                "forfait_deplacement": nombre_format(campagne.contrat_forfait_deplacement),
                "prime_meilleur_vendeur": nombre_format(campagne.prime_meilleur_vendeur),
                "aide_hebdo_active": campagne.aide_hebdo_active,
                "aide_hebdo_montant": nombre_format(campagne.aide_hebdo_montant),
                "aide_hebdo_carburant": nombre_format(campagne.aide_hebdo_carburant),
                "aide_hebdo_credit_tel": nombre_format(campagne.aide_hebdo_credit_tel),
                "clause_libre": campagne.contrat_clause_libre,
                "lieu_signature": campagne.contrat_lieu_signature,
                "date_signature_affichee": contexte["date_signature_affichee"],
            },
            "versements": [
                {
                    "id": v.id,
                    "semaine_debut": v.semaine_debut.strftime("%d/%m/%Y"),
                    "montant_carburant": nombre_format(v.montant_carburant),
                    "montant_credit_tel": nombre_format(v.montant_credit_tel),
                    "accuse_at": v.accuse_at.strftime("%d/%m/%Y %H:%M")
                    if v.accuse_at
                    else None,
                }
                for v in versements
            ],
        },
    )


def _repondre_contrat(request, statut):
    user = request.user
    campagne = _campagne_du_commercial(user)

    if not campagne or not campagne.user_est_signataire_contrat(user):
        deposer_flash(request, error="Campagne ou habilitation invalide.")
        return redirect("/mon-contrat")

    reponse = ContratPrestationReponse.objects.filter(
        campagne_id=campagne.id, user_id=user.id
    ).first()
    if reponse is None:
        raise Http404

    if (
        campagne.contrat_delai_expire()
        or reponse.statut != StatutReponseContrat.EN_ATTENTE
    ):
        deposer_flash(
            request,
            error="Vous ne pouvez plus modifier votre réponse "
            "(la campagne est terminée ou votre décision est déjà enregistrée).",
        )
        return redirect("/mon-contrat")

    if not campagne.contrat_publie_at:
        deposer_flash(
            request, error="Le contrat n’a pas encore été publié par l’administrateur."
        )
        return redirect("/mon-contrat")

    reponse.statut = statut
    reponse.repondu_at = datetime.now().replace(microsecond=0)
    reponse.save(update_fields=["statut", "repondu_at"])

    deposer_flash(
        request,
        success="Contrat accepté. Merci."
        if statut == StatutReponseContrat.ACCEPTE
        else "Contrat refusé. La direction en sera informée.",
    )
    return redirect("/mon-contrat")


@role_required(Role.COMMERCIAL, Role.COMMERCIAL_TELEPHONIQUE)
@http_methods("POST")
def contrat_accepter(request):
    return _repondre_contrat(request, StatutReponseContrat.ACCEPTE)


@role_required(Role.COMMERCIAL, Role.COMMERCIAL_TELEPHONIQUE)
@http_methods("POST")
def contrat_rejeter(request):
    return _repondre_contrat(request, StatutReponseContrat.REJETE)


@role_required(Role.COMMERCIAL, Role.COMMERCIAL_TELEPHONIQUE)
@http_methods("POST")
def versement_accuser(request, versement):
    versement = get_object_or_404(CampagneAideVersement, pk=versement)
    if versement.user_id != request.user.id:
        raise PermissionDenied
    if versement.accuse_at:
        deposer_flash(request, error="Ce versement est déjà accusé réception.")
        return redirect(request.META.get("HTTP_REFERER") or "/mon-contrat")

    versement.accuse_at = datetime.now().replace(microsecond=0)
    versement.accuse_commentaire = request.POST.get("accuse_commentaire") or None
    versement.save(update_fields=["accuse_at", "accuse_commentaire"])

    deposer_flash(request, success="Réception des aides enregistrée.")
    return redirect(request.META.get("HTTP_REFERER") or "/mon-contrat")


# ---------------------------------------------------------------------------
# Reporting téléphonique
# ---------------------------------------------------------------------------


@role_required(Role.COMMERCIAL_TELEPHONIQUE)
@http_methods("GET", "HEAD")
def telephonique_index(request):
    user = request.user
    agence_id = int(user.agence_id) if user.agence_id else None
    base = services.restreindre_aux_campagnes_vente(
        TelephoniqueRapport.objects.filter(user_id=user.id),
        agence_id,
        user.partenaire_id,
    )

    def formater(r):
        return {
            "id": r.id,
            "date_iso": r.date_rapport.strftime("%Y-%m-%d"),
            "date": r.date_rapport.strftime("%d/%m/%Y"),
            "appels_emis": r.appels_emis,
            "appels_joignables": r.appels_joignables,
            "appels_non_joignables": r.appels_non_joignables,
            "taux_joignabilite": f"{nombre_format(r.taux_joignabilite, 2)} %"
            if r.taux_joignabilite is not None
            else None,
            "clients_interesses_nombre": r.clients_interesses_nombre,
            "peut_modifier": r.peut_etre_modifie_ou_supprime(),
        }

    return render(
        request,
        "Commercial/Telephonique/Index",
        {
            "libelleStatsCampagne": services.libelle_stats(
                agence_id, TypeCampagne.VENTE_CARTE, user.partenaire_id
            ),
            "totauxListe": totaux_telephonique(base),
            "rapports": paginer(
                request, base.order_by("-date_rapport", "-id"), 20, formater
            ),
        },
    )


def _types_cartes_campagne(user):
    """Types proposés au reporting, selon la campagne active du périmètre."""
    Campagne.sync_statuts()
    actives = list(Campagne.actives_pour_commercial(user)[:1])
    campagne = actives[0] if actives else None
    if campagne is None:
        return None, []
    return campagne, list(campagne.types_cartes_pour_reporting_telephonique())


@role_required(Role.COMMERCIAL_TELEPHONIQUE)
@http_methods("GET", "HEAD")
def telephonique_create(request):
    user = request.user
    jour = request.GET.get("date") or date.today().strftime("%Y-%m-%d")
    rapport = TelephoniqueRapport.objects.filter(
        user_id=user.id, date_rapport=jour
    ).first()

    campagne, types = _types_cartes_campagne(user)

    return render(
        request,
        "Commercial/Telephonique/Form",
        {
            "dateRapport": jour,
            "campagneActiveNom": campagne.nom if campagne else None,
            "rapportVerrouille": bool(rapport and not rapport.peut_etre_modifie_ou_supprime()),
            "typesCampagne": [{"id": t.id, "code": t.code} for t in types],
            "rapport": {
                "appels_emis": rapport.appels_emis,
                "appels_joignables": rapport.appels_joignables,
                "taux_joignabilite": rapport.taux_joignabilite,
                "clients_interesses_nombre": rapport.clients_interesses_nombre,
                "clients_deja_servis_nombre": rapport.clients_deja_servis_nombre,
                "nj_repondeur": rapport.nj_repondeur,
                "nj_numero_errone": rapport.nj_numero_errone,
                "nj_hors_reseau": rapport.nj_hors_reseau,
                "nj_autres_nombre": rapport.nj_autres_nombre,
                "nj_autres_precision": rapport.nj_autres_precision,
                "propose": {
                    str(t.id): rapport.nombre_propose_pour_type(t.id) for t in types
                },
            }
            if rapport
            else None,
        },
    )


@role_required(Role.COMMERCIAL_TELEPHONIQUE)
@http_methods("POST")
def telephonique_store(request):
    user = request.user
    _, types = _types_cartes_campagne(user)
    source = request.POST

    validateur = Validateur(source)
    validateur.champ("date_rapport", "required|date")
    for champ in (
        "appels_emis",
        "appels_joignables",
        "clients_interesses_nombre",
        "clients_deja_servis_nombre",
        "nj_repondeur",
        "nj_numero_errone",
        "nj_hors_reseau",
        "nj_autres_nombre",
    ):
        validateur.champ(champ, "required|integer|min:0")
    validateur.champ("nj_autres_precision", "nullable|max:500")
    for type_carte in types:
        validateur.champ(f"propose.{type_carte.id}", "required|integer|min:0")

    try:
        donnees = validateur.resultat()
    except ErreursValidation as erreur:
        return retour_avec_erreurs(request, erreur.erreurs)

    autres = donnees["nj_autres_nombre"]
    if autres > 0 and not (donnees.get("nj_autres_precision") or "").strip():
        return retour_avec_erreurs(
            request,
            {
                "nj_autres_precision": "Précisez le motif « autres » lorsque le nombre est supérieur à 0."
            },
        )

    emis, joignables = donnees["appels_emis"], donnees["appels_joignables"]
    if joignables > emis:
        return retour_avec_erreurs(
            request,
            {
                "appels_joignables": "Le nombre de joignables ne peut pas dépasser les appels émis."
            },
        )

    non_joignables = max(0, emis - joignables)
    somme_motifs = (
        donnees["nj_repondeur"]
        + donnees["nj_numero_errone"]
        + donnees["nj_hors_reseau"]
        + autres
    )
    if somme_motifs > non_joignables:
        return retour_avec_erreurs(
            request,
            {
                "nj_analyse": "La somme (répondeur + n° erroné + hors réseau + autres) ne peut "
                "pas dépasser le nombre de non joignables (appels émis − joignables), soit "
                f"{non_joignables} pour cette fiche."
            },
        )

    jour = donnees["date_rapport"]
    existante = TelephoniqueRapport.objects.filter(
        user_id=user.id, date_rapport=jour
    ).first()
    if existante and not existante.peut_etre_modifie_ou_supprime():
        return retour_avec_erreurs(
            request,
            {
                "date_rapport": "Cette fiche ne peut plus être modifiée : délai de 48 h "
                "dépassé depuis l’enregistrement."
            },
        )

    agence_id = int(user.agence_id) if user.agence_id else None
    campagne_fiche = Campagne.pour_fiche_telephonique(
        agence_id, date.fromisoformat(jour), user.partenaire_id
    )

    valeurs = {
        "campagne_id": campagne_fiche.id if campagne_fiche else None,
        "appels_emis": emis,
        "appels_joignables": joignables,
        "appels_non_joignables": non_joignables,
        "taux_joignabilite": round(joignables / emis * 100, 2) if emis > 0 else None,
        "clients_interesses_nombre": donnees["clients_interesses_nombre"],
        "clients_interesses_pct": None,
        "clients_deja_servis_nombre": donnees["clients_deja_servis_nombre"],
        "clients_deja_servis_pct": None,
        "cartes_proposees": {
            str(t.id): donnees[f"propose.{t.id}"] for t in types
        },
        # Colonnes de l'ancien format, conservées à zéro pour l'historique.
        "propose_visa": 0,
        "propose_gim": 0,
        "propose_cauris": 0,
        "propose_prepayee": 0,
        "nj_repondeur": donnees["nj_repondeur"],
        "nj_numero_errone": donnees["nj_numero_errone"],
        "nj_hors_reseau": donnees["nj_hors_reseau"],
        "nj_autres_nombre": autres,
        "nj_autres_precision": (donnees.get("nj_autres_precision") or "").strip()
        if autres > 0
        else None,
    }

    TelephoniqueRapport.objects.update_or_create(
        user_id=user.id, date_rapport=jour, defaults=valeurs
    )

    deposer_flash(request, success="Fiche enregistrée.")
    return redirect("/reporting-telephonique")


@role_required(Role.COMMERCIAL_TELEPHONIQUE)
@http_methods("POST", "DELETE")
def telephonique_destroy(request, telephoniqueRapport):
    rapport = get_object_or_404(TelephoniqueRapport, pk=telephoniqueRapport)
    if rapport.user_id != request.user.id:
        raise PermissionDenied

    if not rapport.peut_etre_modifie_ou_supprime():
        deposer_flash(
            request,
            error="Suppression impossible : délai de 48 h dépassé depuis l’enregistrement de cette fiche.",
        )
        return redirect("/reporting-telephonique")

    rapport.delete()
    deposer_flash(request, success="Fiche supprimée.")
    return redirect("/reporting-telephonique")
