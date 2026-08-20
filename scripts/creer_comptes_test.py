#!/usr/bin/env python
"""
Crée (ou remet à niveau) les comptes de test de la base de développement.

Un compte par rôle, pour que le banc de comparaison puisse rejouer les écrans
réservés à chacun. À n'exécuter que sur `bdm_dev` — jamais en production.

Usage :
    backend/.venv/Scripts/python.exe scripts/creer_comptes_test.py
"""

import os
import sys

sys.path.insert(0, os.path.join(os.path.dirname(__file__), "..", "backend"))
os.environ.setdefault("DJANGO_SETTINGS_MODULE", "config.settings")

import django  # noqa: E402

django.setup()

from django.conf import settings  # noqa: E402

from campagnes.models import Campagne, CampagneCommercialContrat, ContratPrestationReponse  # noqa: E402
from core.auth_backend import hacher_mot_de_passe  # noqa: E402
from core.models import Partenaire, Role, User  # noqa: E402

MOT_DE_PASSE = "TestMigration#2026"

COMPTES = [
    {"email": "test.migration@bdm.local", "name": "MIGRATION", "prenom": "Test",
     "role": Role.ADMIN, "agence": False},
    {"email": "test.direction@bdm.local", "name": "DIRECTION", "prenom": "Test",
     "role": Role.DIRECTION, "agence": False},
    {"email": "test.commercial@bdm.local", "name": "COMMERCIAL", "prenom": "Test",
     "role": Role.COMMERCIAL, "agence": True},
    {"email": "test.telephonique@bdm.local", "name": "TELEPHONIQUE", "prenom": "Test",
     "role": Role.COMMERCIAL_TELEPHONIQUE, "agence": True},
]


def garde_fou():
    """Refuse de s'exécuter ailleurs que sur la base de développement."""
    base = settings.DATABASES["default"]["NAME"]
    if base != "bdm_dev":
        raise SystemExit(
            f"Refus : ce script ne doit tourner que sur `bdm_dev` (base courante : {base})."
        )


def main():
    garde_fou()

    # Les comptes de test sont ceux de la BDM : c'est son périmètre que le banc
    # de comparaison rejoue, et un compte sans client ne verrait aucun écran.
    partenaire = Partenaire.objects.filter(code="bdm").first()

    # Les comptes commerciaux sont rattachés à l'agence de la campagne active,
    # sans quoi les écrans de vente et de contrat resteraient vides.
    campagnes = Campagne.objects.filter(actif=True)
    if partenaire:
        campagnes = campagnes.filter(partenaire_id=partenaire.id)
    campagne = campagnes.order_by("-date_debut").first()

    agence_id = None
    if campagne:
        # `agences_perimetre()` renvoie une liste, pas un queryset.
        agences = campagne.agences_perimetre()
        agence_id = agences[0].id if agences else None

    for definition in COMPTES:
        user, cree = User.objects.get_or_create(
            email=definition["email"],
            defaults={
                "name": definition["name"],
                "prenom": definition["prenom"],
                "role": definition["role"],
                "actif": True,
                "password": hacher_mot_de_passe(MOT_DE_PASSE),
            },
        )
        user.role = definition["role"]
        user.actif = True
        user.password = hacher_mot_de_passe(MOT_DE_PASSE)
        user.agence_id = agence_id if definition["agence"] else None
        # L'administrateur et la direction choisissent leur client à la
        # connexion ; les commerciaux sont rattachés au leur.
        user.partenaire_id = (
            partenaire.id if (partenaire and definition["agence"]) else None
        )
        user.save()

        # Engagement sur la campagne active pour les profils commerciaux :
        # signataire du contrat, réponse acceptée.
        if definition["agence"] and campagne:
            CampagneCommercialContrat.objects.get_or_create(
                campagne_id=campagne.id, user_id=user.id
            )
            ContratPrestationReponse.objects.update_or_create(
                campagne_id=campagne.id,
                user_id=user.id,
                defaults={"statut": "accepte"},
            )

        etat = "créé" if cree else "mis à jour"
        print(f"  #{user.id:<4} {user.email:<32} {user.role:<24} {etat}")

    print(f"\nCampagne de rattachement : {campagne.nom if campagne else '(aucune active)'}")
    print(f"Agence : #{agence_id}")
    print(f"Mot de passe commun : {MOT_DE_PASSE}")


if __name__ == "__main__":
    main()
