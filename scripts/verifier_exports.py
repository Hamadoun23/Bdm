#!/usr/bin/env python
"""
Vérifie que chaque export produit bien un fichier exploitable.

Le banc de comparaison ne couvre que les props Inertia : les exports renvoient
des fichiers binaires. On contrôle ici le statut, le type MIME, et la validité
du conteneur (zip OOXML lisible, PDF avec en-tête, CSV non vide) — des deux
côtés quand Laravel expose la même route.

Les comptes utilisés sont ceux de la production, restaurés dans `bdm_dev` : il
faut donc fournir leurs identifiants. Les exports d'un rôle dont le mot de passe
n'est pas donné sont ignorés, et signalés comme tels.

Usage :
    backend/.venv/Scripts/python.exe scripts/verifier_exports.py \\
        --admin Cisse --mot-de-passe-admin '<mot de passe>'
"""

import argparse
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

#: Client de GDA sous lequel les exports sont vérifiés (cf. migration
#: core.0001_partenaires, qui pose la BDM à l'identifiant 1).
PARTENAIRE_PAR_DEFAUT = 1

#: Rempli par les arguments de la ligne de commande : {rôle: (identifiant, mdp)}.
COMPTES = {}

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

VERT, ROUGE, CYAN, JAUNE, FIN = (
    "\033[32m", "\033[31m", "\033[36m", "\033[33m", "\033[0m"
)


class Client:
    def __init__(self, base, identifiant, mot_de_passe):
        self.base = base.rstrip("/")
        self.mot_de_passe = mot_de_passe
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
            {"email": self.identifiant, "password": self.mot_de_passe, "_token": jeton},
            {"Referer": self.base + "/login", "X-CSRF-TOKEN": jeton, "X-CSRFToken": jeton},
        )
        self._choisir_client()
        return self

    def _choisir_client(self):
        """
        Sélectionne le client de GDA si le compte en pilote plusieurs.

        Sans ce choix, un administrateur est redirigé vers `/choix-client` et
        chaque export renvoie une page HTML au lieu d'un fichier.
        """
        html = self._requete("/choix-client").read().decode("utf-8", "replace")
        trouve = re.search(r'csrf-token"\s+content="([^"]+)"', html)
        jeton = trouve.group(1) if trouve else ""
        # Sans effet pour un commercial : la vue le renvoie au tableau de bord.
        self._requete(
            "/choix-client",
            {"partenaire_id": PARTENAIRE_PAR_DEFAUT, "_token": jeton},
            {"Referer": self.base + "/choix-client", "X-CSRF-TOKEN": jeton,
             "X-CSRFToken": jeton},
        )


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

    # Une page HTML à la place d'un fichier trahit une redirection — session
    # expirée, ou client de GDA non choisi. C'est un échec, pas un « texte ».
    if type_contenu == "text/html":
        return statut, type_contenu, len(contenu), "PAGE HTML"

    return statut, type_contenu, len(contenu), "texte"


def main():
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument("--base", default=DJANGO)
    analyseur.add_argument("--admin", default="Cisse")
    analyseur.add_argument("--mot-de-passe-admin", required=True)
    analyseur.add_argument(
        "--commercial", default="", help="téléphone d'un commercial terrain"
    )
    analyseur.add_argument("--mot-de-passe-commercial", default="")
    analyseur.add_argument(
        "--telephonique", default="", help="téléphone d'une téléopératrice"
    )
    analyseur.add_argument("--mot-de-passe-telephonique", default="")
    options = analyseur.parse_args()

    COMPTES["admin"] = (options.admin, options.mot_de_passe_admin)
    if options.commercial and options.mot_de_passe_commercial:
        COMPTES["commercial"] = (options.commercial, options.mot_de_passe_commercial)
    if options.telephonique and options.mot_de_passe_telephonique:
        COMPTES["telephonique"] = (
            options.telephonique,
            options.mot_de_passe_telephonique,
        )

    sessions = {}

    def session(role, base):
        cle = (role, base)
        if cle not in sessions:
            identifiant, mot_de_passe = COMPTES[role]
            sessions[cle] = Client(base, identifiant, mot_de_passe).connexion()
        return sessions[cle]

    print(f"{CYAN}Vérification des exports — Django {options.base}{FIN}\n")
    echecs = ignores = 0

    for chemin, role in EXPORTS:
        if role not in COMPTES:
            ignores += 1
            print(f"{JAUNE}IGN {FIN} {chemin}")
            print(f"       {role:<13} aucun identifiant fourni pour ce rôle")
            continue

        client = session(role, options.base)
        statut, type_contenu, taille, verdict = decrire(client._requete(chemin))

        valide = statut == 200 and verdict not in (
            "VIDE", "zip corrompu", "zip illisible", "PAGE HTML"
        )
        if not valide:
            echecs += 1

        marque = f"{VERT}OK  {FIN}" if valide else f"{ROUGE}KO  {FIN}"
        print(f"{marque} {chemin}")
        print(f"       {role:<13} {verdict:<18} {taille:>8} o   {type_contenu}")

    print()
    if echecs:
        print(f"{ROUGE}{echecs} export(s) en échec{FIN}")
        return 1
    suffixe = f" ({ignores} ignoré(s), faute d'identifiants)" if ignores else ""
    print(f"{VERT}Tous les exports vérifiés produisent un fichier valide{suffixe}.{FIN}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
