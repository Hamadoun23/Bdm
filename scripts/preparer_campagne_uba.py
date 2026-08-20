#!/usr/bin/env python
"""
Installe le client UBA en local : catalogue, commerciaux et campagne d'août 2026.

Les données viennent de docs/UBA/ :

- « LISTE DES COMMERCIAUX VENTES DE CARTES GDA.xlsx » — les dix commerciaux
  externes de la campagne GDA/UBA. Ils n'ont pas d'agence : chez UBA, un
  commercial dépend directement du client.
- « Convention (CARTES VISA PREPAYEES AFRICARDS PERSONNE PHYSIQUE).pdf » — la
  carte vendue, une VISA prépayée AfriCards émise par UBA Mali.

Le script est **idempotent** : relancé, il met à jour au lieu de dupliquer.

Sur toute base autre que `bdm_dev`, il exige `--production` : installer un
client est une opération que l'on ne déclenche pas par inadvertance.

Les comptes sont créés avec un mot de passe provisoire — connu en local pour
pouvoir tester, aléatoire ailleurs. Dans les deux cas il est immédiatement
remplacé par `scripts/acces_commerciaux_uba.py`, qui attribue à chacun le sien.

Usage :
    backend/.venv/Scripts/python.exe scripts/preparer_campagne_uba.py
    python scripts/preparer_campagne_uba.py --production
"""

import argparse
import os
import secrets
import sys
from datetime import date, datetime

sys.path.insert(0, os.path.join(os.path.dirname(__file__), "..", "backend"))
os.environ.setdefault("DJANGO_SETTINGS_MODULE", "config.settings")

import django  # noqa: E402

django.setup()

from django.conf import settings  # noqa: E402
from django.db import transaction  # noqa: E402

from campagnes.models import (  # noqa: E402
    Campagne,
    CampagneCommercialContrat,
    ContratPrestationReponse,
    StatutCampagne,
    StatutReponseContrat,
    TypeCampagne,
)
from campagnes.services import creer_articles_par_defaut_si_absents  # noqa: E402
from core.auth_backend import hacher_mot_de_passe  # noqa: E402
from core.models import Partenaire, Role, TypeCarte, User  # noqa: E402

BASE_DEVELOPPEMENT = "bdm_dev"

#: Mot de passe provisoire posé à la création des comptes, en développement
#: seulement. Il est remplacé par `scripts/acces_commerciaux_uba.py`, qui
#: attribue à chacun le sien et imprime la fiche d'accès distribuée aux
#: commerciaux. Relancer ce script-ci n'écrase rien : les comptes existants
#: gardent leur mot de passe.
MOT_DE_PASSE_INITIAL = "BdmTest#2026"

#: Extrait de « LISTE DES COMMERCIAUX VENTES DE CARTES GDA.xlsx ».
#: Le premier numéro sert d'identifiant de connexion ; les commerciaux qui en
#: déclarent deux gardent le second en mémoire, il n'a pas d'usage applicatif.
COMMERCIAUX = [
    ("MAIGA", "Fatou", "76208554"),
    ("SISSOKO", "Isac Oscar Junior", "76422027"),
    ("DIARRA", "Sidi", "92125797"),
    ("N'DIAYE", "Soya", "78820625"),
    ("SANGARE", "Adiaratou", "75182604"),
    ("HAIDARA", "Neissa", "77167376"),
    ("YARO", "Yacouba", "83784758"),
    ("TOURE", "Abdoul Kadiri", "70405509"),
    ("MAIGA", "Abdoulaye", "71177096"),
    ("KEITA", "Fousseyni", "75747751"),
]

#: La carte vendue par cette campagne — VISA prépayée AfriCards, émise par UBA.
CARTE = "GDA_VISA_PREPAYEE"

#: Dates reprises du contrat de prestation signé par les commerciaux
#: (docs/UBA/Contrat_prestation_services_commerciaux_GDA_UBA_*.docx) : les
#: articles citent ces dates, elles doivent être celles de la campagne.
CAMPAGNE = {
    "nom": "Campagne GDA/UBA Août 2026",
    "date_debut": date(2026, 8, 17),
    "date_fin": date(2026, 9, 17),
}


def _verifier_base(production):
    """
    Autorise la base courante, et renvoie (nom, mot de passe provisoire).

    Hors développement, le mot de passe provisoire est aléatoire : personne ne
    doit pouvoir se connecter avec pendant le court instant qui sépare la
    création des comptes de l'attribution des vrais mots de passe.
    """
    base = settings.DATABASES["default"]["NAME"]
    developpement = base == BASE_DEVELOPPEMENT

    if not developpement and not production:
        raise SystemExit(
            f"REFUS : base « {base} » (hors développement). Installer un client "
            "est une opération délibérée : relancez avec --production."
        )

    return base, MOT_DE_PASSE_INITIAL if developpement else secrets.token_urlsafe(24)


def _partenaire_uba():
    partenaire = Partenaire.objects.filter(code="uba").first()
    if partenaire is None:
        raise SystemExit(
            "Le partenaire UBA est absent : appliquez d'abord les migrations "
            "(`manage.py migrate core`)."
        )
    return partenaire


def _type_carte(partenaire):
    type_carte, cree = TypeCarte.objects.get_or_create(
        code=CARTE, defaults={"actif": True, "partenaire_id": partenaire.id}
    )
    if not cree:
        type_carte.actif = True
        type_carte.partenaire_id = partenaire.id
        type_carte.save()
    return type_carte, cree


def _commerciaux(partenaire, mot_de_passe_initial):
    """Crée ou met à jour les dix commerciaux, sans agence."""
    resultats = []

    for nom, prenom, telephone in COMMERCIAUX:
        user = User.objects.filter(telephone=telephone).first()
        cree = user is None
        if cree:
            # Le mot de passe n'est posé qu'à la création : une relance ne doit
            # pas révoquer l'accès déjà distribué à un commercial.
            user = User(
                telephone=telephone,
                password=hacher_mot_de_passe(mot_de_passe_initial),
            )

        user.name = nom
        user.prenom = prenom
        user.role = Role.COMMERCIAL
        user.agence_id = None
        user.partenaire_id = partenaire.id
        user.actif = True
        # Les commerciaux se connectent par téléphone : pas d'e-mail.
        user.email = None
        user.save()
        resultats.append((user, cree))

    return resultats


def _campagne(partenaire, commerciaux):
    campagne = Campagne.objects.filter(
        partenaire_id=partenaire.id, nom=CAMPAGNE["nom"]
    ).first()
    cree = campagne is None

    if cree:
        campagne = Campagne(
            partenaire_id=partenaire.id,
            nom=CAMPAGNE["nom"],
            type=TypeCampagne.VENTE_CARTE,
            statut=StatutCampagne.PROGRAMMEE,
            actif=False,
        )

    campagne.date_debut = CAMPAGNE["date_debut"]
    campagne.date_fin = CAMPAGNE["date_fin"]
    # UBA n'a pas d'agences : le périmètre par agence est sans objet, et les
    # commerciaux engagés sont désignés un à un par le pivot du contrat.
    campagne.toutes_agences = True
    campagne.contrat_tous_commerciaux = False
    # Le contrat doit être publié pour que les commerciaux puissent y répondre ;
    # une republication remettrait à zéro des réponses déjà données.
    if campagne.contrat_publie_at is None:
        campagne.contrat_publie_at = datetime.now().replace(microsecond=0)
    campagne.save()

    # Signataires du contrat = les dix commerciaux de la liste.
    for user, _ in commerciaux:
        CampagneCommercialContrat.objects.get_or_create(
            campagne_id=campagne.id, user_id=user.id
        )
        ContratPrestationReponse.objects.get_or_create(
            campagne_id=campagne.id,
            user_id=user.id,
            defaults={"statut": StatutReponseContrat.EN_ATTENTE},
        )

    creer_articles_par_defaut_si_absents(campagne.id, TypeCampagne.VENTE_CARTE)
    return campagne, cree


def main():
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument(
        "--production",
        action="store_true",
        help="autorise l'exécution sur une base autre que bdm_dev",
    )
    options = analyseur.parse_args()

    base, mot_de_passe_initial = _verifier_base(options.production)
    partenaire = _partenaire_uba()

    with transaction.atomic():
        type_carte, carte_creee = _type_carte(partenaire)
        commerciaux = _commerciaux(partenaire, mot_de_passe_initial)
        campagne, campagne_creee = _campagne(partenaire, commerciaux)

    Campagne.sync_statuts()
    campagne.refresh_from_db()

    print(f"Base            : {base}")
    print(f"Client          : {partenaire.nom} — {partenaire.nom_complet}")
    print(f"Organisation    : {partenaire.get_organisation_display()}")
    print(
        f"Type de carte   : {type_carte.code} "
        f"({'créé' if carte_creee else 'déjà présent'})"
    )
    print(
        f"Campagne        : « {campagne.nom} » "
        f"({'créée' if campagne_creee else 'mise à jour'}) — "
        f"{campagne.date_debut:%d/%m/%Y} au {campagne.date_fin:%d/%m/%Y}, "
        f"statut {campagne.statut_effectif}"
    )
    print()

    nouveaux = sum(1 for _, cree in commerciaux if cree)
    print(f"Commerciaux     : {len(commerciaux)} ({nouveaux} créés)")
    if nouveaux:
        provisoire = (
            mot_de_passe_initial
            if base == BASE_DEVELOPPEMENT
            else "aléatoire (inutilisable en l'état)"
        )
        print(f"  Mot de passe provisoire des comptes créés : {provisoire}")
    for user, cree in commerciaux:
        marque = "+" if cree else " "
        print(f"  {marque} {user.telephone:<12} {user.nom_complet}")

    print()
    print("Attribuer à chacun son mot de passe et imprimer la fiche d'accès :")
    print("  backend/.venv/Scripts/python.exe scripts/acces_commerciaux_uba.py")
    print()
    print("Le contrat de prestation est publié mais pas encore accepté : chaque")
    print("commercial doit l'accepter depuis « Mon contrat » avant de vendre.")


if __name__ == "__main__":
    main()
