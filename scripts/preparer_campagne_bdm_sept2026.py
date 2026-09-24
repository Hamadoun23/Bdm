#!/usr/bin/env python
"""
Installe la campagne BDM de septembre 2026 : redéploiement de 25 commerciaux
sur de nouvelles agences, et vente des cartes BDM du 16/09 au 16/10/2026.

Les données viennent de « docs/Sept/BDM Sept campagnes LISTE DES COMMERCIAUX.pdf »
(« LISTE DE REDEPLOIEMENT DES COMMERCIAUX SEPTEMBRE 2026 »).

21 des 25 commerciaux existent déjà en base (retrouvés par téléphone) : ce
script les RÉAFFECTE à leur nouvelle agence — c'est le sens du mot
« redéploiement » du document source. Les 4 autres sont créés.

Un cas particulier : la ligne « DEMBELE Salimata » du PDF porte le téléphone
72789105, absent de la base. Le téléphone 72189105 (un seul chiffre d'écart)
y correspond exactement — même nom, et déjà affecté à KOROFINA, l'agence visée
par le redéploiement. Il s'agit très probablement d'une même personne mal
recopiée sur le PDF : ce script réutilise donc ce compte existant plutôt que
d'en créer un doublon. À confirmer auprès du client si un doute subsiste.

Le script ne touche jamais le nom/prénom d'un commercial déjà en base : deux
listes précédentes (docs/LISE_Commerciaux_BDM-PI.xlsx, la fiche d'accès
d'août) orthographient déjà certains noms différemment du PDF de septembre
(ex. SAGONO/SANOGO, YALCOYE/YACOULYE) — mieux vaut garder l'orthographe déjà
en base que la réécrire à chaque campagne.

Le script est **idempotent** : relancé, il met à jour au lieu de dupliquer.

Sur toute base autre que `bdm_dev`, il exige `--production`.

Les comptes sont créés avec un mot de passe provisoire — connu en local pour
pouvoir tester, aléatoire ailleurs. Dans les deux cas il est immédiatement
remplacé par `scripts/acces_commerciaux_bdm_sept2026.py`, qui attribue à
chacun le sien et produit le fichier Excel de diffusion.

Usage :
    backend/.venv/Scripts/python.exe scripts/preparer_campagne_bdm_sept2026.py
    python scripts/preparer_campagne_bdm_sept2026.py --production
"""

import argparse
import os
import secrets
import sys
from datetime import date, datetime

#: En local, scripts/ et backend/ sont deux dossiers frères du dépôt. Dans le
#: conteneur (COPY backend/ . dans backend/Dockerfile), le contenu de backend/
#: est copié à la racine /app — scripts/ y est un sous-dossier, et « backend »
#: n'existe pas comme tel. On teste les deux dispositions.
_ICI = os.path.dirname(__file__)
_BACKEND = os.path.join(_ICI, "..", "backend")
if not os.path.isdir(_BACKEND):
    _BACKEND = os.path.join(_ICI, "..")
sys.path.insert(0, _BACKEND)
os.environ.setdefault("DJANGO_SETTINGS_MODULE", "config.settings")

import django  # noqa: E402

django.setup()

from django.conf import settings  # noqa: E402
from django.db import transaction  # noqa: E402

from campagnes.models import (  # noqa: E402
    Campagne,
    CampagneAgence,
    CampagneCommercialContrat,
    ContratPrestationReponse,
    StatutCampagne,
    StatutReponseContrat,
    TypeCampagne,
)
from campagnes.services import creer_articles_par_defaut_si_absents  # noqa: E402
from core.auth_backend import hacher_mot_de_passe  # noqa: E402
from core.models import Agence, Partenaire, Role, User  # noqa: E402

BASE_DEVELOPPEMENT = "bdm_dev"

#: Mot de passe provisoire posé à la création des comptes, en développement
#: seulement. Remplacé par `scripts/acces_commerciaux_bdm_sept2026.py`.
MOT_DE_PASSE_INITIAL = "BdmTest#2026"

#: Nom d'agence tel qu'il figure sur le PDF -> nom exact en base. Nécessaire
#: car plusieurs agences sont orthographiées différemment sur le document
#: (ex. « QUINZABOUGOU » sur le PDF, « QUINZAMBOUGOU » en base).
AGENCE_PAR_NOM_PDF = {
    "NIAMANA": "Niamana",
    "QUINZABOUGOU": "QUINZAMBOUGOU",
    "MISSIRA": "MISSIRA",
    "DIBIDA": "Dibida",
    "AZAR CENTER": "AZAR CENTER",
    "SOGONIKO": "Sogoniko",
    "DJICORONI-PARA": "DJICORONI-PARA",
    "SEBENIKORO": "SEBENIKORO",
    "PME/PMI": "PME/PMI",
    "BAGADADJI": "BAGADADJI",
    "FUTURA": "Futura",
    "NGOLONINA": "N'Golonina",
    "BANCONI": "BANCONI RAZEL",
    "HAMDALLAYE": "HAMDALLAYE",
    "AP2": "AP2",
    "KOROFINA": "Korofina",
    "TOROKORO": "TOROKORO",
    "SENOU": "Senou",
    "SEMA GESCO": "SEMA GESCO",
    "LAFIABOUGOU": "LAFIABOUGOU",
    "SEGOU 2": "Ségou 2",
    "SAN": "San",
    "KOULIKORO": "Koulikoro",
    "SIKASSO 1": "SIKASSO 1",
    "KAYES 1": "Kayes 1",
    # Renfort du 21/09/2026 (4 commerciaux supplémentaires).
    "SOTUBA": "Sotuba",
    "KALANBA COURA": "Kalaban coura",
    "DRAMANE DIAKITE": "Dramane DIAKITE",
    "YIRIMADIO": "Yirimadio",
    # Renfort du 23/09/2026.
    "BACO-DJICORONI": "Baco Djicoroni",
}

#: (nom, prénom, téléphone, clé d'agence PDF). Extrait de
#: « BDM Sept campagnes LISTE DES COMMERCIAUX.pdf ».
COMMERCIAUX = [
    ("THERA", "Mariam", "74082712", "NIAMANA"),
    ("CAMARA", "ALY BADRA", "73907530", "QUINZABOUGOU"),
    ("KANSAYE", "Diahara", "78522819", "MISSIRA"),
    # Permutée avec SANGARE Dougo le 23/09/2026 (redéploiement demandé).
    ("MAIGA", "Adiaratou A", "90889198", "YIRIMADIO"),
    ("COULIBALY", "Aminata", "71766277", "AZAR CENTER"),
    ("SANGARE", "Fatimata", "78754962", "SOGONIKO"),
    ("TOURE", "Mary N", "69098738", "DJICORONI-PARA"),
    ("KONATE", "Maimouna", "70179839", "SEBENIKORO"),
    ("FOFANA", "Kadiatou", "76612042", "PME/PMI"),
    ("SAGONO", "Fatoumata", "71010050", "BAGADADJI"),
    ("COULIBALY", "Awa", "79790604", "FUTURA"),
    ("TOGORA", "Lassina", "83140127", "NGOLONINA"),
    ("KOUYATE", "Cheick Sadibou", "92045573", "BANCONI"),
    ("SIDIBE", "Djelika KEITA", "72715555", "HAMDALLAYE"),
    ("DIARRA", "Assetou YALCOYE", "66986621", "AP2"),
    # Téléphone corrigé (72789105 sur le PDF) — voir la note en tête de fichier.
    ("DEMBELE", "Salimata", "72189105", "KOROFINA"),
    ("GAKOU", "Oumar", "79787541", "SENOU"),
    # Téléphone corrigé le 21/09/2026 (74548282 sur le PDF était erroné).
    ("DICKO", "Djeneba", "74548228", "SEMA GESCO"),
    ("TOURE", "NANA ALASSANE", "73006222", "LAFIABOUGOU"),
    ("THIAM", "Mohamed Aly", "70442854", "SEGOU 2"),
    ("THERA", "Hawa", "62036940", "SAN"),
    ("SANOGO", "Fatoumata", "92330460", "KOULIKORO"),
    ("DEMBELE", "Karidiata", "60625221", "SIKASSO 1"),
    ("SISSOKO", "Djeneba", "69418521", "KAYES 1"),
    # Renfort du 21/09/2026 (4 commerciaux supplémentaires).
    ("DIARRA", "Djeneba", "93804215", "SOTUBA"),
    ("SAMAKE", "Assetou", "94875294", "KALANBA COURA"),
    ("TRAORE", "FATOUMATA A.", "71676717", "DRAMANE DIAKITE"),
    # Permutée avec MAIGA Adiaratou A. le 23/09/2026 (redéploiement demandé).
    ("SANGARE", "Dougo", "76036596", "DIBIDA"),
    # Renfort du 23/09/2026. Ce téléphone correspond à un compte existant
    # (id 47, créé en avril 2026 pour la Campagne Juin 2026, désactivé depuis,
    # agence MAGNAMBOUGOU) : redéployé sur Baco Djicoroni plutôt que dupliqué.
    ("COULIBALY", "MAMADOU BODIE", "76411856", "BACO-DJICORONI"),
    # Renfort du 24/09/2026. Redéployée depuis SEBENIKORO.
    ("COULIBALY", "Fatoumata", "92666022", "DRAMANE DIAKITE"),
    # Remplace THIAM Fatoumata (désistement, cf. COMMERCIAUX_RETIRES) sur TOROKORO.
    ("SANGARA", "KADIATOU", "77046778", "TOROKORO"),
]

#: (nom, prénom, téléphone). Commerciaux retirés de la campagne — désistement,
#: etc. Le script les désengage (contrat + réponse supprimés du périmètre de
#: la campagne) et désactive leur compte.
COMMERCIAUX_RETIRES = [
    # Désistement le 24/09/2026, remplacée par SANGARA Kadiatou sur TOROKORO.
    ("THIAM", "Fatoumata", "92274352"),
]

CAMPAGNE = {
    "nom": "Campagne BDM Septembre 2026",
    "date_debut": date(2026, 9, 17),
    "date_fin": date(2026, 10, 18),
}


def _verifier_base(production):
    base = settings.DATABASES["default"]["NAME"]
    developpement = base == BASE_DEVELOPPEMENT

    if not developpement and not production:
        raise SystemExit(
            f"REFUS : base « {base} » (hors développement). Redéployer des "
            "commerciaux est une opération délibérée : relancez avec --production."
        )

    return base, MOT_DE_PASSE_INITIAL if developpement else secrets.token_urlsafe(24)


def _partenaire_bdm():
    partenaire = Partenaire.objects.filter(code="bdm").first()
    if partenaire is None:
        raise SystemExit("Le partenaire BDM est absent de cette base.")
    return partenaire


def _agences(partenaire):
    """Résout chaque clé PDF vers l'Agence en base ; erreur si une manque."""
    resolues = {}
    manquantes = []
    for cle, nom_base in AGENCE_PAR_NOM_PDF.items():
        agence = Agence.objects.filter(nom__iexact=nom_base).first()
        if agence is None:
            manquantes.append(nom_base)
        else:
            resolues[cle] = agence
    if manquantes:
        raise SystemExit(f"Agences introuvables en base : {manquantes}")
    return resolues


def _commerciaux(partenaire, agences_par_cle, mot_de_passe_initial):
    """
    Crée les commerciaux absents, RÉAFFECTE l'agence des commerciaux déjà
    présents (c'est le redéploiement), sans jamais toucher leur nom/prénom.
    """
    resultats = []

    for nom, prenom, telephone, cle_agence in COMMERCIAUX:
        agence = agences_par_cle[cle_agence]
        user = User.objects.filter(telephone=telephone).first()
        cree = user is None

        if cree:
            user = User(
                name=nom,
                prenom=prenom,
                telephone=telephone,
                password=hacher_mot_de_passe(mot_de_passe_initial),
            )

        agence_changee = not cree and user.agence_id != agence.id
        user.role = Role.COMMERCIAL
        user.agence_id = agence.id
        user.partenaire_id = partenaire.id
        user.actif = True
        user.save()
        resultats.append((user, cree, agence_changee))

    return resultats


def _campagne(partenaire, agences_par_cle, commerciaux):
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
    # Périmètre restreint aux agences et commerciaux du redéploiement, pas
    # « toutes agences » : les 29 autres agences BDM ne sont pas concernées
    # par cette campagne de septembre.
    campagne.toutes_agences = False
    campagne.contrat_tous_commerciaux = False
    if campagne.contrat_publie_at is None:
        campagne.contrat_publie_at = datetime.now().replace(microsecond=0)
    campagne.save()

    for agence in {a.id: a for a in agences_par_cle.values()}.values():
        CampagneAgence.objects.get_or_create(campagne_id=campagne.id, agence_id=agence.id)

    for user, _, _ in commerciaux:
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


def _retirer_commerciaux(campagne):
    """
    Désengage les commerciaux de `COMMERCIAUX_RETIRES` de cette campagne
    (désistement, etc.) et désactive leur compte — ils ne doivent plus
    apparaître dans le périmètre ni pouvoir se connecter.
    """
    resultats = []
    for nom, prenom, telephone in COMMERCIAUX_RETIRES:
        user = User.objects.filter(telephone=telephone).first()
        if user is None:
            continue

        CampagneCommercialContrat.objects.filter(
            campagne_id=campagne.id, user_id=user.id
        ).delete()
        ContratPrestationReponse.objects.filter(
            campagne_id=campagne.id, user_id=user.id
        ).delete()

        if user.actif:
            user.actif = False
            user.save(update_fields=["actif"])

        resultats.append(user)
    return resultats


def main():
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument(
        "--production",
        action="store_true",
        help="autorise l'exécution sur une base autre que bdm_dev",
    )
    options = analyseur.parse_args()

    base, mot_de_passe_initial = _verifier_base(options.production)
    partenaire = _partenaire_bdm()
    agences_par_cle = _agences(partenaire)

    with transaction.atomic():
        commerciaux = _commerciaux(partenaire, agences_par_cle, mot_de_passe_initial)
        campagne, campagne_creee = _campagne(partenaire, agences_par_cle, commerciaux)
        retires = _retirer_commerciaux(campagne)

    Campagne.sync_statuts()
    campagne.refresh_from_db()

    print(f"Base            : {base}")
    print(f"Client          : {partenaire.nom} — {partenaire.nom_complet}")
    print(
        f"Campagne        : « {campagne.nom} » "
        f"({'créée' if campagne_creee else 'mise à jour'}) — "
        f"{campagne.date_debut:%d/%m/%Y} au {campagne.date_fin:%d/%m/%Y}, "
        f"statut {campagne.statut_effectif}"
    )
    print()

    nouveaux = sum(1 for _, cree, _ in commerciaux if cree)
    redeployes = sum(1 for _, cree, changee in commerciaux if not cree and changee)
    print(
        f"Commerciaux     : {len(commerciaux)} "
        f"({nouveaux} créés, {redeployes} réaffectés, "
        f"{len(commerciaux) - nouveaux - redeployes} inchangés)"
    )
    for user, cree, changee in commerciaux:
        marque = "+" if cree else ("~" if changee else " ")
        print(f"  {marque} {user.telephone:<12} {user.nom_complet:<28} -> {user.agence.nom}")

    if retires:
        print()
        print(f"Retirés         : {len(retires)} (désengagés et compte désactivé)")
        for user in retires:
            print(f"  - {user.telephone:<12} {user.nom_complet}")

    print()
    print("Attribuer à chacun son mot de passe et produire le fichier Excel :")
    print("  backend/.venv/Scripts/python.exe scripts/acces_commerciaux_bdm_sept2026.py")


if __name__ == "__main__":
    main()
