#!/usr/bin/env python
"""
Compare la table de routes de Laravel (Ziggy) à celle générée par Django.

Les 199 appels `route(...)` du frontend ne fonctionnent que si chaque nom de
route existe des deux côtés avec la même URI. Ce script liste ce qui manque
encore à porter et signale les URI divergentes.

Usage :
    python scripts/comparer_routes.py
    python scripts/comparer_routes.py --restantes   # seulement ce qui reste à faire
"""

import argparse
import json
import re
import sys
import urllib.request
from html import unescape
from http.cookiejar import CookieJar
from pathlib import Path

LARAVEL = "http://127.0.0.1:8002"
DJANGO = "http://127.0.0.1:8001"

RACINE = Path(__file__).resolve().parent.parent

VERT, ROUGE, JAUNE, CYAN, FIN = "\033[32m", "\033[31m", "\033[33m", "\033[36m", "\033[0m"


def _html(base, chemin="/login"):
    cookies = CookieJar()
    opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cookies))
    with opener.open(base + chemin, timeout=30) as reponse:
        return reponse.read().decode("utf-8", "replace")


def routes_laravel():
    """Ziggy est injecté par la directive Blade `@routes` sous forme `const Ziggy={…}`."""
    html = _html(LARAVEL)
    trouve = re.search(r"const Ziggy\s*=\s*(\{.*?\});", html, re.DOTALL)
    if not trouve:
        raise SystemExit("Objet Ziggy introuvable sur la page Laravel.")
    return json.loads(trouve.group(1))["routes"]


def routes_django():
    html = _html(DJANGO)
    trouve = re.search(r"window\.Ziggy\s*=\s*(\{.*?\});", html, re.DOTALL)
    if not trouve:
        raise SystemExit("Objet Ziggy introuvable sur la page Django.")
    return json.loads(unescape(trouve.group(1)))["routes"]


def routes_utilisees_par_le_frontend():
    """Noms de routes réellement appelés dans les pages React."""
    noms = set()
    for fichier in (RACINE / "frontend" / "src").rglob("*.jsx"):
        for nom in re.findall(r"route\(\s*'([a-zA-Z0-9._-]+)'", fichier.read_text(encoding="utf-8")):
            noms.add(nom)
    return noms


def main():
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument("--restantes", action="store_true")
    options = analyseur.parse_args()

    laravel = routes_laravel()
    django = routes_django()
    utilisees = routes_utilisees_par_le_frontend()

    manquantes = sorted(set(laravel) - set(django))
    en_trop = sorted(set(django) - set(laravel))
    communes = sorted(set(laravel) & set(django))

    divergentes = [
        (nom, laravel[nom]["uri"], django[nom]["uri"])
        for nom in communes
        if laravel[nom]["uri"].rstrip("/") != django[nom]["uri"].rstrip("/")
    ]

    print(f"{CYAN}Laravel : {len(laravel)} routes | Django : {len(django)} routes{FIN}")
    print(f"{CYAN}Appelées par le frontend : {len(utilisees)}{FIN}\n")

    if not options.restantes:
        print(f"{VERT}Portées et identiques : {len(communes) - len(divergentes)}{FIN}")

    if divergentes:
        print(f"\n{ROUGE}URI divergentes ({len(divergentes)}){FIN}")
        for nom, uri_l, uri_d in divergentes:
            print(f"  {nom}\n      laravel : {uri_l}\n      django  : {uri_d}")

    if en_trop:
        print(f"\n{JAUNE}Présentes seulement côté Django ({len(en_trop)}){FIN}")
        for nom in en_trop:
            print(f"  {nom}")

    # Les routes manquantes qui sont appelées par le frontend cassent une page :
    # elles sont prioritaires sur celles qui ne servent qu'en interne.
    bloquantes = [nom for nom in manquantes if nom in utilisees]
    autres = [nom for nom in manquantes if nom not in utilisees]

    if bloquantes:
        print(f"\n{ROUGE}À porter — appelées par le frontend ({len(bloquantes)}){FIN}")
        for nom in bloquantes:
            print(f"  {nom:<55} {laravel[nom]['uri']}")

    if autres:
        print(f"\n{JAUNE}À porter — non appelées par le frontend ({len(autres)}){FIN}")
        for nom in autres:
            print(f"  {nom:<55} {laravel[nom]['uri']}")

    print()
    if not manquantes and not divergentes:
        print(f"{VERT}Toutes les routes Laravel sont portées à l'identique.{FIN}")
        return 0
    print(f"{ROUGE}{len(manquantes)} route(s) à porter, {len(divergentes)} URI divergente(s){FIN}")
    return 1


if __name__ == "__main__":
    sys.exit(main())
