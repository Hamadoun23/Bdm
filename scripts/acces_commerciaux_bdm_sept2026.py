#!/usr/bin/env python
"""
Pose les mots de passe des commerciaux de la campagne BDM Septembre 2026 et
produit le fichier Excel de diffusion (identifiant, agence, mot de passe).

Reprend la convention déjà en usage chez BDM (cf. `core/exports` — l'écran
d'import de commerciaux en pose une aussi, `generer_mot_de_passe_initial`) :

    initiale du prénom + deux derniers chiffres du téléphone
    + initiale du nom + « @bdm »

    Mariam THERA, 74082712  →  M12T@bdm

Chaque mot de passe est donc propre à son porteur et ne fonctionne qu'avec
son numéro.

Sur toute base autre que `bdm_dev`, il exige `--production` : le script
écrit des mots de passe, on ne le déclenche pas par inadvertance.

Usage :
    backend/.venv/Scripts/python.exe scripts/acces_commerciaux_bdm_sept2026.py
    python scripts/acces_commerciaux_bdm_sept2026.py --production
"""

import argparse
import os
import sys
from datetime import date

sys.path.insert(0, os.path.join(os.path.dirname(__file__), "..", "backend"))
os.environ.setdefault("DJANGO_SETTINGS_MODULE", "config.settings")

sys.stdout.reconfigure(encoding="utf-8", errors="replace")

import django  # noqa: E402

django.setup()

from django.conf import settings  # noqa: E402
from openpyxl import Workbook  # noqa: E402
from openpyxl.styles import Alignment, Font, PatternFill  # noqa: E402
from openpyxl.utils import get_column_letter  # noqa: E402

from campagnes.models import Campagne  # noqa: E402
from campagnes.services import generer_mot_de_passe_initial  # noqa: E402
from core.auth_backend import hacher_mot_de_passe  # noqa: E402
from core.models import Partenaire  # noqa: E402

BASE_DEVELOPPEMENT = "bdm_dev"
NOM_CAMPAGNE = "Campagne BDM Septembre 2026"

DOSSIER_SORTIE = os.path.join(os.path.dirname(__file__), "..", "docs", "Sept")
NOM_FICHIER = "Acces Campagne BDM Septembre 2026.xlsx"

BORDEAUX = "381419"
BLANC = "FFFFFF"
GRIS_CLAIR = "F2F2F2"


def construire_classeur(commerciaux, site, periode):
    classeur = Workbook()
    feuille = classeur.active
    feuille.title = "Accès commerciaux"

    feuille.merge_cells("A1:D1")
    feuille["A1"] = f"Accès à l'application — {NOM_CAMPAGNE}"
    feuille["A1"].font = Font(size=14, bold=True, color=BORDEAUX)

    feuille.merge_cells("A2:D2")
    feuille["A2"] = f"Vente des cartes BDM · {periode} · {site}"
    feuille["A2"].font = Font(size=10, italic=True, color="595959")

    feuille.merge_cells("A3:D3")
    feuille["A3"] = (
        "Identifiant = numéro de téléphone (chiffres uniquement). Le mot de "
        "passe respecte les majuscules/minuscules : 1re et dernière lettre en "
        "MAJUSCULE, « @bdm » en minuscules."
    )
    feuille["A3"].font = Font(size=9, color="8C8C8C")

    entetes = ["Nom", "Agence", "Identifiant (téléphone)", "Mot de passe"]
    ligne_entete = 5
    for colonne, intitule in enumerate(entetes, start=1):
        cellule = feuille.cell(row=ligne_entete, column=colonne, value=intitule)
        cellule.font = Font(bold=True, color=BLANC)
        cellule.fill = PatternFill("solid", fgColor=BORDEAUX)
        cellule.alignment = Alignment(vertical="center")

    for index, (user, agence_nom, secret) in enumerate(commerciaux):
        ligne = ligne_entete + 1 + index
        feuille.cell(row=ligne, column=1, value=user.nom_complet)
        feuille.cell(row=ligne, column=2, value=agence_nom)
        feuille.cell(row=ligne, column=3, value=user.telephone or "—")
        cellule_mdp = feuille.cell(row=ligne, column=4, value=secret)
        cellule_mdp.font = Font(bold=True, color=BORDEAUX)
        if index % 2 == 1:
            for colonne in range(1, 5):
                feuille.cell(row=ligne, column=colonne).fill = PatternFill(
                    "solid", fgColor=GRIS_CLAIR
                )

    largeurs = (28, 18, 22, 16)
    for colonne, largeur in zip(range(1, 5), largeurs):
        feuille.column_dimensions[get_column_letter(colonne)].width = largeur

    derniere_ligne = ligne_entete + len(commerciaux) + 1
    feuille.merge_cells(f"A{derniere_ligne}:D{derniere_ligne}")
    feuille[f"A{derniere_ligne}"] = (
        f"Document généré le {date.today():%d/%m/%Y} — {len(commerciaux)} "
        f"commerciaux, {NOM_CAMPAGNE}."
    )
    feuille[f"A{derniere_ligne}"].font = Font(size=8, italic=True, color="8C8C8C")

    return classeur


def main():
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument(
        "--site", default="bdm.gdamali.net",
        help="adresse imprimée sur le fichier (défaut : le site actuel)",
    )
    analyseur.add_argument(
        "--sans-ecriture", action="store_true",
        help="génère le fichier sans toucher aux mots de passe en base",
    )
    analyseur.add_argument(
        "--production", action="store_true",
        help="autorise l'exécution sur une base autre que bdm_dev",
    )
    options = analyseur.parse_args()

    base = settings.DATABASES["default"]["NAME"]
    if (
        base != BASE_DEVELOPPEMENT
        and not options.sans_ecriture
        and not options.production
    ):
        raise SystemExit(
            f"REFUS : base « {base} » (hors développement). Ce script écrit des "
            "mots de passe : relancez avec --production."
        )

    partenaire = Partenaire.objects.filter(code="bdm").first()
    if partenaire is None:
        raise SystemExit("Le partenaire BDM est absent de cette base.")

    campagne = Campagne.objects.filter(
        partenaire_id=partenaire.id, nom=NOM_CAMPAGNE
    ).first()
    if campagne is None:
        raise SystemExit(
            f"Campagne « {NOM_CAMPAGNE} » absente : lancez d'abord "
            "`scripts/preparer_campagne_bdm_sept2026.py`."
        )

    users = list(
        campagne.signataires_contrat.select_related("agence").order_by("name", "prenom")
    )
    if not users:
        raise SystemExit(
            "Aucun commercial rattaché à cette campagne : lancez d'abord "
            "`scripts/preparer_campagne_bdm_sept2026.py`."
        )

    commerciaux = [
        (
            user,
            user.agence.nom if user.agence_id else "—",
            generer_mot_de_passe_initial(user.prenom, user.name, user.telephone or ""),
        )
        for user in users
    ]

    doublons = {
        secret
        for _, _, secret in commerciaux
        if [s for _, _, s in commerciaux].count(secret) > 1
    }
    if doublons:
        raise SystemExit(
            f"Collision de mots de passe : {sorted(doublons)}. "
            "Deux commerciaux partageraient le même accès — corrigez la liste."
        )

    if not options.sans_ecriture:
        for user, _, secret in commerciaux:
            user.password = hacher_mot_de_passe(secret)
            user.save(update_fields=["password"])

    periode = f"du {campagne.date_debut:%d/%m/%Y} au {campagne.date_fin:%d/%m/%Y}"
    classeur = construire_classeur(commerciaux, options.site, periode)

    os.makedirs(DOSSIER_SORTIE, exist_ok=True)
    chemin = os.path.abspath(os.path.join(DOSSIER_SORTIE, NOM_FICHIER))
    classeur.save(chemin)

    print(f"Base       : {base}")
    print(f"Client     : {partenaire.nom} — {partenaire.nom_complet}")
    print(f"Campagne   : {campagne.nom} ({periode})")
    print(f"Site       : {options.site}")
    print(
        "Écriture   : "
        + ("aucune (--sans-ecriture)" if options.sans_ecriture else "mots de passe posés")
    )
    print(f"Fichier    : {chemin}\n")

    print(f"{'Nom':<28} {'Agence':<18} {'Téléphone':<12} Mot de passe")
    print("-" * 72)
    for user, agence_nom, secret in commerciaux:
        print(f"{user.nom_complet:<28} {agence_nom:<18} {user.telephone or '—':<12} {secret}")


if __name__ == "__main__":
    main()
