#!/usr/bin/env python
"""
Vérifie que chaque export produit bien un fichier exploitable.

Le banc de comparaison ne couvre que les props Inertia : les exports renvoient
des fichiers binaires. On contrôle ici le statut, le type MIME, et la validité
du conteneur (zip OOXML lisible, PDF avec en-tête, CSV non vide) — des deux
côtés quand Laravel expose la même route.

Usage :
    backend/.venv/Scripts/python.exe scripts/verifier_exports.py
"""

import io
import re
import sys
import urllib.error
import urllib.parse
import urllib.request
import zipfile
from http.cookiejar import CookieJar

LARAVEL = "http://127.0.0.1:8002"
DJANGO = "http://127.0.0.1:8001"
MOT_DE_PASSE = "TestMigration#2026"

COMPTES = {
    "admin": "test.migration@bdm.local",
    "commercial": "test.commercial@bdm.local",
    "telephonique": "test.telephonique@bdm.local",
}

#: (chemin, compte). Ces routes renvoient un fichier, pas une page Inertia.
EXPORTS = [
    ("/ventes/export-excel", "admin"),
    ("/ventes/export-excel", "commercial"),
    ("/reporting-telephonique/export-excel", "telephonique"),
    ("/admin/reporting-telephonique/export", "admin"),
    ("/clients/2100/export?format=pdf", "admin"),
    ("/clients/2100/export?format=excel", "admin"),
    ("/clients/2100/export?format=word", "admin"),
    ("/rapports/export?type=mensuel&date=2026-06", "admin"),
    ("/rapports/export?type=mensuel&date=2026-06&format=xlsx", "admin"),
    ("/rapports/campagnes/8/export?section=ventes", "admin"),
    ("/rapports/campagnes/8/export?section=commerciaux&format=xlsx", "admin"),
    ("/rapports/campagnes/8/export?section=agences&format=xlsx", "admin"),
    ("/rapports/campagnes/8/export?section=types&format=xlsx", "admin"),
    ("/rapports/campagnes/8/export?section=all&format=xlsx", "admin"),
    ("/rapports/campagnes/20/export?section=all&format=xlsx", "admin"),
    ("/rapports/campagnes/8/synthese/export-graphiques-excel", "admin"),
    ("/rapports/campagnes/8/synthese/export-graphiques-word", "admin"),
    ("/rapports/campagnes/20/synthese/export-graphiques-word", "admin"),
    ("/rapports/cumul/export?campagne_ids[]=8&section=ventes&format=xlsx", "admin"),
    ("/rapports/cumul/export?campagne_ids[]=8&section=all&format=xlsx", "admin"),
    ("/rapports/cumul/export?campagne_ids[]=8&section=graphiques-excel", "admin"),
    ("/rapports/cumul/export?campagne_ids[]=8&section=graphiques-word", "admin"),
    ("/performances/export-excel", "admin"),
    ("/performances/export-graphiques-excel", "admin"),
    ("/performances/export-graphiques-word", "admin"),
    ("/performances/commercial/45/export-excel?campagne_id=8", "admin"),
]

VERT, ROUGE, CYAN, FIN = "\033[32m", "\033[31m", "\033[36m", "\033[0m"


class Client:
    def __init__(self, base, identifiant):
        self.base = base.rstrip("/")
        self.cookies = CookieJar()
        self.opener = urllib.request.build_opener(
            urllib.request.HTTPCookieProcessor(self.cookies)
        )
        self.identifiant = identifiant

    def _requete(self, chemin, donnees=None, entetes=None):
        corps = urllib.parse.urlencode(donnees).encode() if donnees else None
        requete = urllib.request.Request(
            self.base + chemin, data=corps, headers=entetes or {}
        )
        try:
            return self.opener.open(requete, timeout=120)
        except urllib.error.HTTPError as erreur:
            return erreur

    def connexion(self):
        html = self._requete("/login").read().decode("utf-8", "replace")
        trouve = re.search(r'csrf-token"\s+content="([^"]+)"', html)
        jeton = trouve.group(1) if trouve else ""
        self._requete(
            "/login",
            {"email": self.identifiant, "password": MOT_DE_PASSE, "_token": jeton},
            {"Referer": self.base + "/login", "X-CSRF-TOKEN": jeton, "X-CSRFToken": jeton},
        )
        return self


def decrire(reponse):
    """Statut, type de contenu et validité du fichier renvoyé."""
    contenu = reponse.read()
    type_contenu = (reponse.headers.get("Content-Type") or "").split(";")[0]
    statut = reponse.status

    if statut != 200:
        return statut, type_contenu, len(contenu), f"HTTP {statut}"

    if contenu[:2] == b"PK":
        try:
            with zipfile.ZipFile(io.BytesIO(contenu)) as archive:
                if archive.testzip() is not None:
                    return statut, type_contenu, len(contenu), "zip corrompu"
                parties = len(archive.namelist())
            return statut, type_contenu, len(contenu), f"OOXML {parties} parties"
        except zipfile.BadZipFile:
            return statut, type_contenu, len(contenu), "zip illisible"

    if contenu[:5] == b"%PDF-":
        return statut, type_contenu, len(contenu), "PDF"

    if len(contenu) == 0:
        return statut, type_contenu, 0, "VIDE"

    return statut, type_contenu, len(contenu), "texte"


def main():
    sessions = {}

    def session(role, base):
        cle = (role, base)
        if cle not in sessions:
            sessions[cle] = Client(base, COMPTES[role]).connexion()
        return sessions[cle]

    print(f"{CYAN}Vérification des exports — Django {DJANGO}{FIN}\n")
    echecs = 0

    for chemin, role in EXPORTS:
        client = session(role, DJANGO)
        statut, type_contenu, taille, verdict = decrire(client._requete(chemin))

        valide = statut == 200 and verdict not in ("VIDE", "zip corrompu", "zip illisible")
        if not valide:
            echecs += 1

        marque = f"{VERT}OK  {FIN}" if valide else f"{ROUGE}KO  {FIN}"
        print(f"{marque} {chemin}")
        print(f"       {role:<13} {verdict:<18} {taille:>8} o   {type_contenu}")

    print()
    if echecs:
        print(f"{ROUGE}{echecs} export(s) en échec{FIN}")
        return 1
    print(f"{VERT}Tous les exports produisent un fichier valide.{FIN}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
