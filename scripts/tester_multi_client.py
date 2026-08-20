#!/usr/bin/env python
"""
Vérifie le cloisonnement par client de GDA, et la vente UBA de bout en bout.

Le risque de cette fonctionnalité n'est pas qu'un écran plante : c'est qu'il
affiche les données du mauvais client. Ce banc pose donc des questions de
contenu, pas seulement de statut HTTP — combien de campagnes, lesquelles, quels
commerciaux, quelles cartes.

Il exerce ensuite une vente UBA complète, avec sa demande d'adhésion, et
vérifie en base ce qui a été écrit. Tout est annulé à la fin.

Prérequis :
    docker compose -f docker-compose.dev.yml up -d
    backend/.venv/Scripts/python.exe backend/manage.py migrate
    backend/.venv/Scripts/python.exe scripts/preparer_campagne_uba.py
    backend/.venv/Scripts/python.exe backend/manage.py runserver 8001

Les comptes repris de la production gardent leurs mots de passe : il faut donc
fournir celui d'un administrateur réel. Le commercial UBA, lui, est créé par
`preparer_campagne_uba.py` et son mot de passe suit la convention de
`acces_commerciaux_uba.py`.

Usage :
    backend/.venv/Scripts/python.exe scripts/tester_multi_client.py \\
        --admin Cisse --mot-de-passe-admin '<le mot de passe>'
"""

import argparse
import io
import os
import sys

sys.path.insert(0, os.path.dirname(__file__))
sys.path.insert(0, os.path.join(os.path.dirname(__file__), "..", "backend"))

sys.stdout.reconfigure(encoding="utf-8", errors="replace")

from tester_ecritures import CYAN, FIN, Navigateur, resultat  # noqa: E402

os.environ.setdefault("DJANGO_SETTINGS_MODULE", "config.settings")
import django  # noqa: E402

django.setup()

from campagnes.models import Campagne, ContratPrestationReponse, StatutReponseContrat  # noqa: E402
from core.models import Partenaire, User  # noqa: E402
from terrain.models import AdhesionCarte, Client, Vente  # noqa: E402

#: Le commercial UBA qui sert de cobaye — le premier de la liste officielle.
TELEPHONE_UBA = "76208554"

#: Mot de passe de ce commercial, tel que l'attribue `acces_commerciaux_uba.py` :
#: initiale du prénom, deux derniers chiffres du téléphone, initiale du nom.
MOT_DE_PASSE_UBA = "F54M@uba"


def _onglets_classeur(navigateur, campagne_id):
    """Noms des feuilles du classeur Excel complet d'une campagne."""
    reponse = navigateur.requete(
        f"/rapports/campagnes/{campagne_id}/export?section=all&format=xlsx",
        inertia=False,
    )
    contenu = reponse.read()
    if contenu[:2] != b"PK":
        return [f"(pas un xlsx — HTTP {reponse.status})"]

    import openpyxl

    return openpyxl.load_workbook(io.BytesIO(contenu)).sheetnames


def _noms(props, cle="campagnes"):
    bloc = props.get(cle) or {}
    lignes = bloc.get("data", bloc) if isinstance(bloc, dict) else bloc
    return [ligne.get("nom") for ligne in lignes]


def main():
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument("--base", default="http://127.0.0.1:8001")
    analyseur.add_argument("--admin", default="Cisse")
    analyseur.add_argument(
        "--mot-de-passe-admin",
        required=True,
        help="mot de passe du compte administrateur (celui de la production)",
    )
    analyseur.add_argument("--mot-de-passe-uba", default=MOT_DE_PASSE_UBA)
    options = analyseur.parse_args()

    print(f"{CYAN}Cible : {options.base}{FIN}\n")
    echecs = 0

    uba = Partenaire.objects.get(code="uba")
    bdm = Partenaire.objects.get(code="bdm")

    # --- Le choix du client est imposé --------------------------------------
    print("Choix du client")
    admin = Navigateur(options.base)
    admin.requete("/login", inertia=False).read()
    reponse = admin.requete(
        "/login", {"email": options.admin, "password": options.mot_de_passe_admin}
    )
    echecs += resultat(
        "/choix-client" in reponse.headers.get("Location", ""),
        "la connexion admin renvoie sur le choix du client",
        reponse.headers.get("Location", ""),
    )

    reponse = admin.requete("/admin/campagnes", inertia=False)
    echecs += resultat(
        reponse.status == 302 and "/choix-client" in reponse.headers.get("Location", ""),
        "un écran d'administration reste inaccessible sans client choisi",
        f"HTTP {reponse.status}",
    )

    # Les fichiers servis à la racine ne doivent pas tomber dans la garde :
    # une redirection sur `/sw.js` fait échouer l'enregistrement du service
    # worker, justement sur l'écran de choix où le navigateur le tente.
    for chemin in ("/sw.js", "/favicon.ico", "/site.webmanifest"):
        reponse = admin.requete(chemin, inertia=False)
        reponse.read()
        echecs += resultat(
            reponse.status == 200,
            f"{chemin} reste servi sans client choisi",
            f"HTTP {reponse.status} {reponse.headers.get('Location') or ''}",
        )

    props = admin.props("/choix-client")
    codes = sorted(p.get("code") for p in props.get("partenaires", []))
    echecs += resultat(codes == ["bdm", "uba"], "les deux clients sont proposés", str(codes))

    # --- Périmètre BDM ------------------------------------------------------
    print("\nClient BDM")
    admin.choisir_client("bdm")

    props = admin.props("/admin/campagnes")
    noms = _noms(props)
    attendu = list(
        Campagne.objects.filter(partenaire_id=bdm.id).values_list("nom", flat=True)
    )
    echecs += resultat(
        all(nom in attendu for nom in noms) and "Campagne GDA/UBA Août 2026" not in noms,
        "les campagnes listées sont celles de la BDM",
        f"{len(noms)} campagnes",
    )

    props = admin.props("/admin/agences")
    echecs += resultat(
        len(props.get("agences", [])) > 0, "la BDM a bien ses agences",
        f"{len(props.get('agences', []))} agences",
    )

    props = admin.props("/admin/users")
    echecs += resultat(
        props.get("aDesAgences") is True, "l'écran utilisateurs affiche la colonne Agence"
    )

    campagne_uba = Campagne.objects.get(partenaire_id=uba.id)
    reponse = admin.requete(f"/admin/campagnes/{campagne_uba.id}", inertia=False)
    echecs += resultat(
        reponse.status == 404,
        "une campagne UBA est invisible depuis le périmètre BDM",
        f"HTTP {reponse.status}",
    )

    # --- Périmètre UBA ------------------------------------------------------
    print("\nClient UBA")
    admin.choisir_client("uba")

    props = admin.props("/admin/campagnes")
    noms = _noms(props)
    echecs += resultat(
        noms == ["Campagne GDA/UBA Août 2026"],
        "seule la campagne UBA est listée",
        str(noms),
    )

    props = admin.props("/admin/agences")
    echecs += resultat(
        props.get("agences") == [], "UBA n'a aucune agence", str(props.get("agences"))
    )

    props = admin.props("/admin/users")
    echecs += resultat(
        props.get("aDesAgences") is False,
        "l'écran utilisateurs masque la colonne Agence",
    )
    echecs += resultat(
        (props.get("users") or {}).get("total") == 10,
        "les dix commerciaux UBA sont listés",
        f"total={(props.get('users') or {}).get('total')}",
    )

    props = admin.props("/admin/types-cartes")
    codes = [t.get("code") for t in props.get("types", [])]
    echecs += resultat(
        codes == ["GDA_VISA_PREPAYEE"],
        "le catalogue de cartes est celui d'UBA",
        str(codes),
    )

    # La prop partagée « client » doit survivre sur chaque page : une prop de
    # page homonyme l'écraserait et la barre latérale perdrait son sélecteur.
    props = admin.props("/dashboard")
    courant = ((props.get("client") or {}).get("courant") or {})
    echecs += resultat(
        courant.get("nom") == "UBA" and courant.get("a_des_agences") is False,
        "le tableau de bord porte le client courant",
        str(courant.get("nom")),
    )
    echecs += resultat(
        props.get("aDesAgences") is False,
        "le tableau de bord masque le compteur d agences",
    )

    # --- Les rapports ne parlent pas d'agences chez un client qui n'en a pas -
    #
    # C'est la fuite la plus insidieuse : une campagne « toutes agences » chez
    # UBA remontait les cinquante-quatre agences de la BDM dans les filtres,
    # les onglets et les classements.
    print("\nRapports UBA — aucune agence ne doit apparaître")

    props = admin.props(f"/rapports/campagnes/{campagne_uba.id}/synthese")
    echecs += resultat(
        props.get("agencesChoix") == []
        and props.get("agences") == []
        and int((props.get("resume") or {}).get("nb_agences_avec_ventes", -1)) == 0,
        "synthèse : ni filtre, ni onglet, ni compteur d'agences",
        f"agencesChoix={len(props.get('agencesChoix') or [])}, "
        f"agences={len(props.get('agences') or [])}",
    )

    props = admin.props(f"/rapports/campagnes/{campagne_uba.id}/ventes")
    echecs += resultat(
        props.get("agencesChoix") == [],
        "liste des ventes : pas de filtre par agence",
        f"{len(props.get('agencesChoix') or [])} agences proposées",
    )

    props = admin.props(f"/rapports/campagnes/{campagne_uba.id}/reporting-telephonique")
    echecs += resultat(
        props.get("agencesChoix") == [],
        "reporting téléphonique : pas de filtre par agence",
        f"{len(props.get('agencesChoix') or [])} agences proposées",
    )

    props = admin.props("/performances")
    echecs += resultat(
        props.get("aDesAgences") is False
        and props.get("canFilterAgence") is False
        and props.get("agencesSelect") == [],
        "performances : ni filtre, ni classement d'agences",
        f"agencesSelect={len(props.get('agencesSelect') or [])}",
    )

    props = admin.props(f"/rapports/cumul?campagne_ids[]={campagne_uba.id}")
    echecs += resultat(
        props.get("aDesAgences") is False,
        "cumul : les blocs d'agences sont masqués",
        str(props.get("aDesAgences")),
    )

    onglets_uba = _onglets_classeur(admin, campagne_uba.id)
    echecs += resultat(
        "Agences" not in onglets_uba and "Ventes détaillées" in onglets_uba,
        "classeur complet : aucun onglet « Agences »",
        ", ".join(onglets_uba),
    )

    # Et la BDM, elle, garde bien les siennes.
    admin.choisir_client("bdm")
    campagne_bdm = (
        Campagne.objects.filter(partenaire_id=bdm.id, type="vente_carte")
        .order_by("-date_debut")
        .first()
    )
    props = admin.props(f"/rapports/campagnes/{campagne_bdm.id}/synthese")
    echecs += resultat(
        len(props.get("agencesChoix") or []) > 0,
        "la BDM conserve son découpage par agences",
        f"{len(props.get('agencesChoix') or [])} agences",
    )
    echecs += resultat(
        "Agences" in _onglets_classeur(admin, campagne_bdm.id),
        "et son onglet « Agences » dans le classeur complet",
    )
    admin.choisir_client("uba")

    # --- Un commercial UBA vend, avec sa demande d'adhésion ------------------
    print("\nCommercial UBA")
    commercial = User.objects.get(telephone=TELEPHONE_UBA)
    vendeur = Navigateur(options.base).connexion(
        TELEPHONE_UBA, options.mot_de_passe_uba
    )

    props = vendeur.props("/dashboard")
    utilisateur = props.get("auth", {}).get("user", {})
    echecs += resultat(
        utilisateur.get("peut_vendre") is True,
        "le commercial sans agence peut vendre",
        f"agence_id={utilisateur.get('agence_id')}, partenaire_id={utilisateur.get('partenaire_id')}",
    )

    # Le contrat doit être accepté avant toute vente.
    reponse = vendeur.requete("/mon-contrat/accepter", {})
    ContratPrestationReponse.objects.filter(
        campagne_id=campagne_uba.id, user_id=commercial.id
    ).update(statut=StatutReponseContrat.ACCEPTE)
    echecs += resultat(
        reponse.status in (302, 303), "acceptation du contrat", f"HTTP {reponse.status}"
    )

    # Le contrat servi doit être celui du client, pas celui de la BDM.
    props = vendeur.props("/mon-contrat")
    document = props.get("document") or {}
    titres = [a.get("titre") for a in document.get("articles", [])]
    echecs += resultat(
        len(titres) == 10 and titres[0].startswith("Article 1")
        and any("Rémunération et émoluments" in t for t in titres),
        "le contrat UBA est servi, avec ses dix articles",
        f"{len(titres)} articles",
    )
    echecs += resultat(
        document.get("remuneration_dans_articles") is True,
        "la rémunération vient des articles, pas du bloc générique",
    )
    corps = " ".join(a.get("contenu", "") for a in document.get("articles", []))
    echecs += resultat(
        "GDA/UBA" in corps and "BDM" not in corps,
        "aucune mention de la BDM dans le contrat UBA",
    )
    echecs += resultat(
        f"{campagne_uba.date_debut:%d/%m/%Y}" in corps
        and f"{campagne_uba.date_fin:%d/%m/%Y}" in corps,
        "les dates du contrat sont celles de la campagne",
        f"{campagne_uba.date_debut:%d/%m/%Y} → {campagne_uba.date_fin:%d/%m/%Y}",
    )
    # Le pied « Fait à …, le … » est rendu une seule fois, par le document :
    # aucun article ne doit le porter en double.
    echecs += resultat(
        "Fait à" not in corps,
        "aucun article ne redouble le pied de contrat",
    )
    echecs += resultat(
        document.get("date_signature_affichee")
        == f"{campagne_uba.date_debut:%d/%m/%Y}",
        "le contrat est daté de sa prise d'effet, pas du jour d'affichage",
        str(document.get("date_signature_affichee")),
    )

    props = vendeur.props("/ventes/create")
    echecs += resultat(
        props.get("ficheAdhesion") is True,
        "le formulaire de vente présente la demande d'adhésion",
    )
    echecs += resultat(
        [t.get("code") for t in props.get("typesCartes", [])] == ["GDA_VISA_PREPAYEE"],
        "seule la carte GDA est proposée",
        str([t.get("code") for t in props.get("typesCartes", [])]),
    )

    reponse = vendeur.requete(
        "/api/ventes",
        {
            "type_carte_id": props["typesCartes"][0]["id"],
            "campagne_id": campagne_uba.id,
            "prenom": "ZZTest",
            "nom": "ADHESION",
            "telephone": "70000000",
            "ville": "Bamako",
            "quartier": "Hamdallaye",
            "nom_sur_carte": "ZZTEST ADHESION",
            "date_naissance": "1990-05-12",
            "lieu_naissance": "Ségou",
            "nationalite": "Malienne",
            "piece_type": "nina",
            "piece_numero": "NINA-000-TEST",
            "piece_delivree_le": "2020-01-15",
            "piece_expire_le": "2030-01-14",
            "piece_autorite": "Ministère de l'Administration territoriale",
            "numero_compte_uba": "ML00-TEST-0001",
            "profession": "Commerçante",
        },
    )
    corps = reponse.read().decode("utf-8", "replace")
    echecs += resultat(
        reponse.status == 201, "POST /api/ventes", f"HTTP {reponse.status} {corps[:180]}"
    )

    vente = (
        Vente.objects.filter(user_id=commercial.id).order_by("-id").first()
        if reponse.status == 201
        else None
    )
    echecs += resultat(
        vente is not None and vente.agence_id is None,
        "la vente est enregistrée sans agence",
        f"agence_id={vente.agence_id if vente else '—'}",
    )

    adhesion = AdhesionCarte.objects.filter(vente_id=vente.id).first() if vente else None
    echecs += resultat(
        adhesion is not None
        and adhesion.nom_sur_carte == "ZZTEST ADHESION"
        and adhesion.piece_type == "nina"
        and str(adhesion.date_naissance) == "1990-05-12",
        "la demande d'adhésion est enregistrée avec ses champs",
        f"nom_sur_carte={adhesion.nom_sur_carte if adhesion else '—'}",
    )

    # La vente doit apparaître côté admin sous UBA, et nulle part sous BDM.
    props = admin.props("/ventes")
    total_uba = (props.get("ventes") or {}).get("total", 0)
    echecs += resultat(
        total_uba == 1 and props.get("aDesAgences") is False,
        "la vente apparaît dans le périmètre UBA",
        f"total={total_uba}",
    )

    admin.choisir_client("bdm")
    props = admin.props("/clients")
    noms_clients = [
        c.get("nom_complet") for c in (props.get("clients") or {}).get("data", [])
    ]
    echecs += resultat(
        "ZZTest ADHESION" not in noms_clients,
        "le client UBA n'apparaît pas dans le périmètre BDM",
    )

    # --- Nettoyage ----------------------------------------------------------
    print("\nNettoyage")
    if vente is not None:
        client_id = vente.client_id
        AdhesionCarte.objects.filter(vente_id=vente.id).delete()
        Vente.objects.filter(pk=vente.id).delete()
        Client.objects.filter(pk=client_id).delete()
    ContratPrestationReponse.objects.filter(
        campagne_id=campagne_uba.id, user_id=commercial.id
    ).update(statut=StatutReponseContrat.EN_ATTENTE, repondu_at=None)
    echecs += resultat(
        not Vente.objects.filter(user_id=commercial.id).exists(),
        "vente, client et adhésion de test supprimés",
    )

    print()
    if echecs:
        print(f"{echecs} vérification(s) en échec.")
        sys.exit(1)
    print("Le cloisonnement par client tient, et la vente UBA fonctionne.")


if __name__ == "__main__":
    main()
