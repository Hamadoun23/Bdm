#!/usr/bin/env python
"""
Exerce les chemins d'écriture comme le fait un navigateur.

Le banc de comparaison ne rejoue que des requêtes GET, en fabriquant lui-même
les en-têtes. Cela masquait trois défauts d'intégration : le nom du cookie
CSRF, le corps JSON d'Inertia, et les URL absolues derrière le proxy.

Ce script poste réellement : jeton CSRF lu dans le *cookie* (comme axios),
corps en JSON (comme Inertia), et vérifie ensuite en base que la ligne existe.
Tout est annulé à la fin.

Usage :
    backend/.venv/Scripts/python.exe scripts/tester_ecritures.py
    backend/.venv/Scripts/python.exe scripts/tester_ecritures.py --base https://bdm.gdamali.net
"""

import argparse
import json
import re
import sys
import urllib.error
import urllib.parse
import urllib.request
from http.cookiejar import CookieJar

# La console Windows est en cp1252 : sans cela, les fleches et tirets longs
# des messages font echouer l'affichage.
sys.stdout.reconfigure(encoding="utf-8", errors="replace")

VERT, ROUGE, CYAN, JAUNE, FIN = "\033[32m", "\033[31m", "\033[36m", "\033[33m", "\033[0m"


class Navigateur:
    """Client HTTP qui se comporte comme le frontend React."""

    def __init__(self, base):
        self.base = base.rstrip("/")
        self.cookies = CookieJar()
        self.opener = urllib.request.build_opener(
            urllib.request.HTTPCookieProcessor(self.cookies), _SansRedirection()
        )

    def _cookie(self, nom):
        for c in self.cookies:
            if c.name == nom:
                return c.value
        return None

    def requete(self, chemin, donnees=None, entetes=None, inertia=True):
        """
        `donnees` est envoyé en JSON, comme le fait Inertia. Le jeton CSRF est
        lu dans le cookie, comme le fait axios — et non dans la balise meta.
        """
        tetes = dict(entetes or {})
        corps = None

        if donnees is not None:
            corps = json.dumps(donnees).encode()
            tetes["Content-Type"] = "application/json"
            jeton = self._cookie("csrftoken")
            if jeton:
                tetes["X-CSRFToken"] = jeton
            tetes["Referer"] = self.base + chemin

        if inertia:
            tetes.setdefault("X-Requested-With", "XMLHttpRequest")

        requete = urllib.request.Request(self.base + chemin, data=corps, headers=tetes)
        try:
            return self.opener.open(requete, timeout=90)
        except urllib.error.HTTPError as erreur:
            return erreur

    def connexion(self, identifiant, mot_de_passe):
        self.requete("/login", inertia=False).read()
        reponse = self.requete(
            "/login", {"email": identifiant, "password": mot_de_passe, "remember": False}
        )
        emplacement = reponse.headers.get("Location", "")
        if reponse.status != 302 or "/dashboard" not in emplacement:
            raise SystemExit(
                f"Connexion refusée pour {identifiant} (HTTP {reponse.status} → {emplacement})"
            )
        return self

    def props(self, chemin):
        reponse = self.requete(
            chemin, entetes={"X-Inertia": "true", "X-Inertia-Version": "1.0"}
        )
        contenu = reponse.read().decode("utf-8", "replace")
        try:
            return json.loads(contenu).get("props", {})
        except json.JSONDecodeError:
            return {}


class _SansRedirection(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None


def compter(navigateur, chemin, cle):
    """Nombre de lignes actuellement listées sur un écran."""
    props = navigateur.props(chemin)
    bloc = props.get(cle) or {}
    return bloc.get("total", 0) if isinstance(bloc, dict) else len(bloc)


def resultat(ok, libelle, detail=""):
    marque = f"{VERT}OK  {FIN}" if ok else f"{ROUGE}KO  {FIN}"
    print(f"  {marque} {libelle}{'  — ' + detail if detail else ''}")
    return 0 if ok else 1


def main():
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument("--base", default="http://127.0.0.1:8001")
    analyseur.add_argument("--commercial", default="", help="téléphone d'un commercial")
    analyseur.add_argument("--mot-de-passe", default="")
    analyseur.add_argument("--admin", default="cisse")
    analyseur.add_argument("--mot-de-passe-admin", default="23m@bdm")
    options = analyseur.parse_args()

    print(f"{CYAN}Cible : {options.base}{FIN}\n")
    echecs = 0

    # --- Administrateur : lecture, filtres, exports -------------------------
    print("Administrateur")
    admin = Navigateur(options.base).connexion(options.admin, options.mot_de_passe_admin)
    echecs += resultat(True, "connexion (POST JSON + CSRF depuis le cookie)")

    props = admin.props("/dashboard")
    echecs += resultat(
        bool(props.get("auth", {}).get("user")),
        "session partagée dans les props",
        f"role={props.get('auth', {}).get('user', {}).get('role')}",
    )

    reponse = admin.requete("/ventes/export-excel", inertia=False)
    contenu = reponse.read()
    echecs += resultat(
        reponse.status == 200 and contenu[:2] == b"PK",
        "export Excel",
        f"{len(contenu)} octets",
    )

    # --- Écriture : création puis suppression d'une agence ------------------
    #
    # L'agence est le seul objet créable sans effet de bord sur les données
    # métier : ni vente, ni enrôlement, ni contrat n'y sont rattachés tant
    # qu'aucun commercial ne lui est affecté.
    print("\nÉcriture (création puis suppression)")
    nom_test = "ZZ TEST ECRITURE — a supprimer"

    avant = len(admin.props("/admin/agences").get("agences", []))
    reponse = admin.requete("/admin/agences", {"nom": nom_test, "ordre": 9999})
    creee = reponse.status in (302, 303)
    echecs += resultat(creee, "POST /admin/agences", f"HTTP {reponse.status}")

    agences = admin.props("/admin/agences").get("agences", [])
    ligne = next((a for a in agences if a["nom"] == nom_test), None)
    echecs += resultat(
        ligne is not None and len(agences) == avant + 1,
        "la ligne existe bien en base",
        f"{avant} → {len(agences)}",
    )

    if ligne:
        reponse = admin.requete(
            f"/admin/agences/{ligne['id']}", {"_method": "DELETE"}
        )
        restantes = len(admin.props("/admin/agences").get("agences", []))
        echecs += resultat(
            reponse.status in (302, 303) and restantes == avant,
            "suppression",
            f"{len(agences)} → {restantes}",
        )

    # --- Commercial : saisie d'un enrôlement --------------------------------
    if options.commercial and options.mot_de_passe:
        print("\nCommercial — saisie terrain")
        commercial = Navigateur(options.base).connexion(
            options.commercial, options.mot_de_passe
        )
        echecs += resultat(True, "connexion par téléphone")

        avant = compter(commercial, "/enrolements", "enrolements")
        reponse = commercial.requete(
            "/api/enrolements",
            {
                "nom": "TEST",
                "prenom": "Enrolement",
                "numero_compte": "TEST0000000",
                "telephone": "00000000",
                "adresse": "Test migration",
            },
        )
        corps = reponse.read().decode("utf-8", "replace")
        cree = reponse.status == 201
        echecs += resultat(
            cree, "POST /api/enrolements", f"HTTP {reponse.status} {corps[:120]}"
        )

        if cree:
            identifiant = json.loads(corps)["enrolement"]["id"]
            apres = compter(commercial, "/enrolements", "enrolements")
            echecs += resultat(apres == avant + 1, "visible dans la liste", f"{avant} → {apres}")

            reponse = commercial.requete(
                f"/enrolements/{identifiant}", {"_method": "DELETE"}
            )
            final = compter(commercial, "/enrolements", "enrolements")
            echecs += resultat(
                final == avant, "suppression par le commercial", f"{apres} → {final}"
            )
    else:
        print(f"\n{JAUNE}Commercial : ignoré (passer --commercial et --mot-de-passe){FIN}")

    print()
    if echecs:
        print(f"{ROUGE}{echecs} vérification(s) en échec{FIN}")
        return 1
    print(f"{VERT}Toutes les écritures aboutissent et sont bien enregistrées.{FIN}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
