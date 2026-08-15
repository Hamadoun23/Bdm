#!/usr/bin/env python
"""
Banc de comparaison Laravel ↔ Django.

Les deux stacks parlent le même protocole Inertia : une requête portant
l'en-tête `X-Inertia: true` renvoie du JSON (composant + props) au lieu du HTML.
Branchées sur la même base, elles doivent produire des props identiques.

Ce script se connecte aux deux, rejoue une liste de routes GET et signale le
moindre écart. C'est le filet de sécurité de toute la migration : tout écart de
calcul (total, prime, taux, tri) apparaît ici avant la production.

Usage :
    python scripts/comparer_stacks.py
    python scripts/comparer_stacks.py --routes /dashboard /ventes
    python scripts/comparer_stacks.py --verbeux
"""

import argparse
import json
import re
import sys
import urllib.error
import urllib.parse
import urllib.request
from html import unescape
from http.cookiejar import CookieJar

LARAVEL = "http://127.0.0.1:8002"
DJANGO = "http://127.0.0.1:8001"

MOT_DE_PASSE = "TestMigration#2026"

#: Un compte par rôle — créés par scripts/creer_comptes_test.py.
COMPTES = {
    "admin": "test.migration@bdm.local",
    "direction": "test.direction@bdm.local",
    "commercial": "test.commercial@bdm.local",
    "telephonique": "test.telephonique@bdm.local",
}
COMPTE_PAR_DEFAUT = "admin"

#: Routes GET rejouées. À compléter au fil des lots.
#:
#: Une entrée est soit un chemin, soit `(chemin, options)`. Seule option
#: reconnue : `trier`, la liste des chemins de props dont l'ordre des lignes
#: n'est pas garanti (voir TRI_INSTABLE ci-dessous).
ROUTES = [
    "/dashboard",
    # Lot 2 — référentiels admin
    "/admin/agences",
    "/admin/agences/create",
    "/admin/types-cartes",
    "/admin/types-cartes/create",
    "/admin/users",
    "/admin/users?role=commercial",
    "/admin/users?contrat=non_signataire",
    "/admin/users/create",
    # Le journal trie par `logged_in_at` seul côté Laravel : plusieurs
    # connexions tombent à la même seconde et MySQL les rend alors dans un
    # ordre arbitraire, qui change avec le plan d'exécution. Django ajoute un
    # tri secondaire sur l'identifiant pour être reproductible ; on compare
    # donc le contenu des lignes, pas leur ordre.
    #
    # Les pages au-delà de la première ne sont pas comparables : quand des
    # lignes à égalité chevauchent une frontière de page, ce ne sont pas les
    # mêmes qui basculent d'un côté et de l'autre. On teste donc la pagination
    # sur un filtre qui isole un utilisateur, sans égalité possible.
    ("/admin/journal-connexions", {"trier": ["logs.data"]}),
    ("/admin/journal-connexions?user_id=45", {"trier": ["logs.data"]}),
    # Lot 3 — campagnes
    "/admin/campagnes",
    "/admin/campagnes/create",
    "/admin/campagnes/20",
    "/admin/campagnes/20?tab=commerciaux",
    "/admin/campagnes/20?tab=contrat",
    "/admin/campagnes/20?tab=aide",
    "/admin/campagnes/20?tab=historique",
    "/admin/campagnes/20?periode=mois",
    "/admin/campagnes/8",
    "/admin/campagnes/8?tab=performances",
    "/admin/campagnes/5",
    "/admin/campagnes/6",
    "/admin/campagnes/20/edit",
    "/admin/campagnes/8/edit",
    ("/direction/campagnes", {"compte": "direction"}),
    ("/direction/campagnes/20", {"compte": "direction"}),
    ("/dashboard", {"compte": "direction"}),
    # Lot 4 — terrain
    ("/ventes", {"trier": ["ventes.data"]}),
    ("/enrolements", {"trier": ["enrolements.data"]}),
    ("/clients", {"trier": ["clients.data"]}),
    "/clients/2100",
    ("/ventes", {"compte": "direction", "trier": ["ventes.data"]}),
    ("/dashboard", {"compte": "commercial"}),
    ("/ventes", {"compte": "commercial", "trier": ["ventes.data"]}),
    ("/ventes/create", {"compte": "commercial"}),
    ("/enrolements", {"compte": "commercial", "trier": ["enrolements.data"]}),
    ("/enrolements/create", {"compte": "commercial"}),
    ("/mon-contrat", {"compte": "commercial"}),
    ("/dashboard", {"compte": "telephonique"}),
    ("/mon-contrat", {"compte": "telephonique"}),
    ("/reporting-telephonique", {"compte": "telephonique"}),
    ("/reporting-telephonique/saisie", {"compte": "telephonique"}),
    # Lot 5 — rapports et performances
    "/rapports",
    ("/rapports/cumul?campagne_ids[]=8&campagne_ids[]=5", {"trier": ["clients", "ventes.data"]}),
    ("/rapports/cumul?campagne_ids[]=8", {"trier": ["clients", "ventes.data"]}),
    ("/rapports/campagnes/8/ventes", {"trier": ["ventes.data"]}),
    ("/rapports/campagnes/20/ventes", {"trier": ["ventes.data"]}),
    "/rapports/campagnes/8/ventes?agence_id=21",
    ("/rapports/campagnes/8/clients", {"trier": ["clients"]}),
    ("/rapports/campagnes/20/clients", {"trier": ["clients"]}),
    "/rapports/campagnes/8/synthese",
    "/rapports/campagnes/20/synthese",
    "/rapports/campagnes/5/synthese",
    "/rapports/campagnes/8/synthese?du=2026-06-20&au=2026-07-01",
    "/rapports/campagnes/8/reporting-telephonique",
    "/performances",
    "/performances?compare=1",
    "/performances?campagne_id=8",
    "/performances?campagne_id=8&compare=1",
    "/performances?campagne_id=20",
    "/performances?du=2026-06-15&au=2026-07-17&campagne_id=8",
    ("/performances", {"compte": "direction"}),
    ("/performances", {"compte": "commercial"}),
    ("/performances", {"compte": "telephonique"}),
    ("/rapports", {"compte": "direction"}),
    "/performances/commercial/45",
    "/performances/commercial/45?campagne_id=8",
    ("/admin/reporting-telephonique", {"trier": ["rapports.data"]}),
    "/admin/reporting-telephonique?campagne_id=8",
    "/admin/reporting-telephonique/1",
    ("/direction/types-de-cartes", {"compte": "direction"}),
    "/rapports/campagnes/5/reporting-telephonique/1",
    ("/forgot-password", {"anonyme": True}),
]

#: Props volatiles à ignorer : elles diffèrent légitimement d'une stack à
#: l'autre ou d'un instant à l'autre sans traduire d'écart fonctionnel.
PROPS_IGNOREES = {"ziggy", "csrf_token", "jetstream"}

COULEURS = {
    "ok": "\033[32m",
    "ko": "\033[31m",
    "info": "\033[36m",
    "attention": "\033[33m",
    "fin": "\033[0m",
}


def colorer(texte, couleur):
    return f"{COULEURS[couleur]}{texte}{COULEURS['fin']}"


class Client:
    """Client HTTP minimal conservant les cookies de session."""

    def __init__(self, base, nom, identifiant):
        self.base = base.rstrip("/")
        self.nom = nom
        self.identifiant = identifiant
        self.version = None
        self.cookies = CookieJar()
        self.opener = urllib.request.build_opener(
            urllib.request.HTTPCookieProcessor(self.cookies),
            _SansRedirection(),
        )

    def _requete(self, chemin, donnees=None, entetes=None):
        url = self.base + chemin
        corps = urllib.parse.urlencode(donnees).encode() if donnees else None
        requete = urllib.request.Request(url, data=corps, headers=entetes or {})
        try:
            return self.opener.open(requete, timeout=60)
        except urllib.error.HTTPError as erreur:
            return erreur

    def jeton_csrf(self):
        reponse = self._requete("/login")
        html = reponse.read().decode("utf-8", "replace")
        trouve = re.search(r'csrf-token"\s+content="([^"]+)"', html)
        return trouve.group(1) if trouve else ""

    def connexion(self):
        jeton = self.jeton_csrf()
        entetes = {
            "Referer": self.base + "/login",
            "X-CSRF-TOKEN": jeton,  # Laravel
            "X-CSRFToken": jeton,  # Django
        }
        reponse = self._requete(
            "/login",
            {"email": self.identifiant, "password": MOT_DE_PASSE, "_token": jeton},
            entetes,
        )
        if reponse.status not in (200, 302, 303):
            raise RuntimeError(
                f"{self.nom} ({self.identifiant}) : échec de connexion (HTTP {reponse.status})"
            )
        return self

    def _page_html(self, chemin):
        """
        Extrait l'objet page Inertia d'une réponse HTML.

        Les deux stacks ne l'encodent pas pareil : l'adaptateur Laravel (Inertia
        v2) place le JSON dans le corps d'un `<script data-page="app">`, tandis
        qu'inertia-django l'échappe dans l'attribut `data-page` d'un `<div>`.
        """
        html = self._requete(chemin).read().decode("utf-8", "replace")

        dans_script = re.search(
            r'<script[^>]*data-page="app"[^>]*>(.*?)</script>', html, re.DOTALL
        )
        if dans_script:
            brut = dans_script.group(1)
        else:
            dans_attribut = re.search(r'data-page="([^"]+)"', html)
            if not dans_attribut:
                return None
            brut = unescape(dans_attribut.group(1))

        try:
            return json.loads(brut)
        except json.JSONDecodeError:
            return None

    def _lire_version(self, chemin):
        """
        Chaque stack a sa propre version d'assets Inertia (Laravel la calcule
        depuis le manifeste Vite, Django la fixe dans les réglages). Envoyer
        celle de l'autre déclenche un 409 « version conflict » : on lit donc
        celle de chacune sur sa propre page.
        """
        page = self._page_html(chemin)
        return page.get("version") if page else None

    def props(self, chemin):
        if self.version is None:
            self.version = self._lire_version(chemin) or ""

        reponse = self._requete(
            chemin,
            entetes={"X-Inertia": "true", "X-Inertia-Version": self.version},
        )
        contenu = reponse.read().decode("utf-8", "replace")
        if reponse.status != 200:
            return {"__http__": reponse.status, "__corps__": contenu[:400]}
        try:
            return json.loads(contenu).get("props", {})
        except json.JSONDecodeError:
            # Une réponse HTML signifie que la stack a redirigé (session perdue,
            # rôle insuffisant) au lieu de rendre la page.
            return {"__non_json__": contenu[:400]}


class _SansRedirection(urllib.request.HTTPRedirectHandler):
    """Les redirections doivent être observées, pas suivies."""

    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None


def normaliser(valeur):
    """Rend deux structures comparables malgré les écarts de typage PHP/Python."""
    if isinstance(valeur, str) and valeur.startswith(LARAVEL):
        # Les deux stacks écoutent sur des ports différents : l'hôte présent dans
        # les liens de pagination n'est pas un écart fonctionnel.
        valeur = "{base}" + valeur[len(LARAVEL):]
    elif isinstance(valeur, str) and valeur.startswith(DJANGO):
        valeur = "{base}" + valeur[len(DJANGO):]

    if isinstance(valeur, dict):
        return {
            cle: normaliser(sous_valeur)
            for cle, sous_valeur in valeur.items()
            if cle not in PROPS_IGNOREES
        }
    if isinstance(valeur, list):
        return [normaliser(element) for element in valeur]
    if isinstance(valeur, bool):
        return valeur
    if isinstance(valeur, (int, float)):
        # PHP sérialise volontiers « 5 » là où Python produit « 5.0 ».
        return int(valeur) if float(valeur).is_integer() else round(float(valeur), 4)
    if isinstance(valeur, str):
        # Un nombre transmis en chaîne par PHP reste le même nombre.
        try:
            nombre = float(valeur)
            return int(nombre) if nombre.is_integer() else round(nombre, 4)
        except ValueError:
            return valeur
    return valeur


def trier_a_ce_chemin(props, chemin_prop):
    """
    Ordonne une liste de lignes par son contenu, pour comparer un jeu de
    résultats dont l'ordre n'est pas garanti par la base.
    """
    segments = chemin_prop.split(".")
    noeud = props
    for segment in segments[:-1]:
        if not isinstance(noeud, dict) or segment not in noeud:
            return
        noeud = noeud[segment]
    dernier = segments[-1]
    if isinstance(noeud, dict) and isinstance(noeud.get(dernier), list):
        noeud[dernier] = sorted(
            noeud[dernier], key=lambda ligne: json.dumps(ligne, sort_keys=True)
        )


def differences(gauche, droite, chemin=""):
    """Liste des écarts entre deux structures, sous forme (chemin, laravel, django)."""
    ecarts = []

    if isinstance(gauche, dict) and isinstance(droite, dict):
        for cle in sorted(set(gauche) | set(droite)):
            sous_chemin = f"{chemin}.{cle}" if chemin else cle
            if cle not in gauche:
                ecarts.append((sous_chemin, "<absent>", droite[cle]))
            elif cle not in droite:
                ecarts.append((sous_chemin, gauche[cle], "<absent>"))
            else:
                ecarts += differences(gauche[cle], droite[cle], sous_chemin)
        return ecarts

    if isinstance(gauche, list) and isinstance(droite, list):
        if len(gauche) != len(droite):
            ecarts.append((f"{chemin}[]", f"{len(gauche)} éléments", f"{len(droite)} éléments"))
        for index, (g, d) in enumerate(zip(gauche, droite)):
            ecarts += differences(g, d, f"{chemin}[{index}]")
        return ecarts

    if gauche != droite:
        ecarts.append((chemin or "<racine>", gauche, droite))
    return ecarts


def main():
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument("--routes", nargs="*", default=ROUTES)
    analyseur.add_argument("--verbeux", action="store_true")
    options = analyseur.parse_args()

    print(colorer(f"Laravel : {LARAVEL}", "info"))
    print(colorer(f"Django  : {DJANGO}\n", "info"))

    # Une session par rôle et par stack, ouverte à la demande : inutile de se
    # connecter avec un compte dont aucune route testée n'a besoin.
    sessions = {}

    def session(role):
        if role not in sessions:
            if role == "anonyme":
                # Certaines routes ne sont accessibles que déconnecté.
                sessions[role] = (
                    Client(LARAVEL, "Laravel", None),
                    Client(DJANGO, "Django", None),
                )
                print(colorer("Session anonyme (non connectée)", "info"))
            else:
                identifiant = COMPTES[role]
                sessions[role] = (
                    Client(LARAVEL, "Laravel", identifiant).connexion(),
                    Client(DJANGO, "Django", identifiant).connexion(),
                )
                print(colorer(f"Session {role} : {identifiant}", "info"))
        return sessions[role]

    total_ecarts = 0
    for entree in options.routes:
        route, reglages = entree if isinstance(entree, tuple) else (entree, {})
        laravel, django = session(
            "anonyme" if reglages.get("anonyme") else reglages.get("compte", COMPTE_PAR_DEFAUT)
        )

        props_laravel = normaliser(laravel.props(route))
        props_django = normaliser(django.props(route))

        for chemin_prop in reglages.get("trier", []):
            trier_a_ce_chemin(props_laravel, chemin_prop)
            trier_a_ce_chemin(props_django, chemin_prop)

        # Deux erreurs HTTP identiques ne sont pas un écart fonctionnel : seules
        # les pages d'erreur, propres à chaque framework, diffèrent.
        if (
            props_laravel.get("__http__")
            and props_laravel.get("__http__") == props_django.get("__http__")
        ):
            props_laravel.pop("__corps__", None)
            props_django.pop("__corps__", None)

        ecarts = differences(props_laravel, props_django)

        if not ecarts:
            print(f"{colorer('OK  ', 'ok')} {route}  ({len(props_laravel)} props identiques)")
            continue

        total_ecarts += len(ecarts)
        print(f"{colorer('ECART', 'ko')} {route}  ({len(ecarts)} différence(s))")
        for sous_chemin, cote_laravel, cote_django in ecarts[: None if options.verbeux else 15]:
            print(f"    {sous_chemin}")
            print(f"      laravel : {json.dumps(cote_laravel, ensure_ascii=False)[:160]}")
            print(f"      django  : {json.dumps(cote_django, ensure_ascii=False)[:160]}")
        if not options.verbeux and len(ecarts) > 15:
            print(f"    … {len(ecarts) - 15} de plus (relancer avec --verbeux)")

    print()
    if total_ecarts:
        print(colorer(f"{total_ecarts} écart(s) au total", "ko"))
        return 1
    print(colorer("Aucun écart — les deux stacks produisent les mêmes props", "ok"))
    return 0


if __name__ == "__main__":
    sys.exit(main())
