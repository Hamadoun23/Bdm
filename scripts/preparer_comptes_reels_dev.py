#!/usr/bin/env python
"""
Pose un mot de passe connu sur les comptes réels de la base de développement.

Les hachages bcrypt venus de la production sont irréversibles : sans cela, il
est impossible de tester l'application avec de vrais comptes, donc avec de
vraies données rattachées (ventes, enrôlements, engagements de campagne).

Ce script REFUSE de s'exécuter ailleurs que sur `bdm_dev`. Il ne doit jamais
tourner en production : il y écraserait tous les mots de passe.

Usage :
    backend/.venv/Scripts/python.exe scripts/preparer_comptes_reels_dev.py
"""

import os
import sys

sys.path.insert(0, os.path.join(os.path.dirname(__file__), "..", "backend"))
os.environ.setdefault("DJANGO_SETTINGS_MODULE", "config.settings")

import django  # noqa: E402

django.setup()

from django.conf import settings  # noqa: E402

from core.auth_backend import hacher_mot_de_passe  # noqa: E402
from core.models import Role, User  # noqa: E402

MOT_DE_PASSE = "BdmTest#2026"

#: Seule base autorisée. Le nom est vérifié, pas seulement l'hôte : une base de
#: production restaurée en local s'appellerait `bdm`, pas `bdm_dev`.
BASE_AUTORISEE = "bdm_dev"


def main():
    base = settings.DATABASES["default"]["NAME"]
    if base != BASE_AUTORISEE:
        raise SystemExit(
            f"REFUS : ce script ne tourne que sur `{BASE_AUTORISEE}` "
            f"(base courante : {base}). Il écraserait tous les mots de passe."
        )

    hachage = hacher_mot_de_passe(MOT_DE_PASSE)
    modifies = User.objects.exclude(email__startswith="test.").update(password=hachage)

    print(f"Base           : {base}")
    print(f"Comptes traités: {modifies}")
    print(f"Mot de passe   : {MOT_DE_PASSE}\n")

    print("Comment se connecter selon le rôle :\n")

    admins = User.objects.filter(role=Role.ADMIN).exclude(email__startswith="test.")
    print("  ADMIN — identifiant = le NOM (ces comptes n'ont ni e-mail ni téléphone)")
    for u in admins.order_by("id"):
        print(f"    {u.name}")

    direction = User.objects.filter(role=Role.DIRECTION).exclude(
        email__startswith="test."
    )
    print("\n  DIRECTION — identifiant = e-mail ou téléphone")
    for u in direction.order_by("id"):
        print(f"    {u.email or u.telephone}   ({u.nom_complet})")

    print("\n  COMMERCIAL — identifiant = le TÉLÉPHONE")
    from django.db.models import Count

    commerciaux = (
        User.objects.filter(role=Role.COMMERCIAL)
        .exclude(email__startswith="test.")
        .annotate(nb=Count("ventes"))
        .order_by("-nb")[:6]
    )
    for u in commerciaux:
        agence = u.agence.nom if u.agence_id else "—"
        print(f"    {u.telephone:<12} {u.nom_complet:<26} {agence:<14} {u.nb} ventes")


if __name__ == "__main__":
    main()
