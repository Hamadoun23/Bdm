"""
Vues du socle : connexion, déconnexion, manifeste PWA.

Portage de App\\Http\\Controllers\\Auth\\AuthenticatedSessionController et de la
route `pwa.manifest` de routes/web.php.
"""

from datetime import datetime

from django.conf import settings
from django.contrib.auth import login as ouvrir_session
from django.contrib.auth import logout as fermer_session
from django.http import JsonResponse
from django.shortcuts import redirect
from django.templatetags.static import static
from django.views.decorators.csrf import csrf_protect
from inertia import render

from . import throttling
from .auth_backend import (
    MESSAGE_ECHEC,
    LaravelBcryptBackend,
    MESSAGE_COMPTE_DESACTIVE,
    compte_desactive,
)
from .decorators import http_methods
from .middleware import deposer_erreurs
from .models import UserLoginLog

BACKEND_AUTH = "core.auth_backend.LaravelBcryptBackend"


def _ip(request) -> str:
    transmise = request.META.get("HTTP_X_FORWARDED_FOR")
    if transmise:
        return transmise.split(",")[0].strip()
    return request.META.get("REMOTE_ADDR", "")


@csrf_protect
@http_methods("GET", "HEAD", "POST")
def login(request):
    """
    Laravel déclare deux routes sur `login` (GET pour l'affichage, POST pour la
    soumission) dont seule la première est nommée. Django n'accepte qu'une vue
    par chemin : on aiguille donc sur la méthode.
    """
    if request.method == "POST":
        return _login_store(request)
    if request.user.is_authenticated:
        return redirect("/dashboard")
    return render(
        request, "Auth/Login", {"status": request.session.pop("status", None)}
    )


def _login_store(request):
    identifiant = (request.POST.get("email") or "").strip()
    mot_de_passe = request.POST.get("password") or ""
    ip = _ip(request)

    secondes = throttling.trop_de_tentatives(identifiant, ip)
    if secondes:
        deposer_erreurs(
            request,
            email=f"Trop de tentatives de connexion. Veuillez réessayer dans {secondes} secondes.",
        )
        return redirect("/login")

    user = LaravelBcryptBackend().authenticate(
        request, username=identifiant, password=mot_de_passe
    )

    if user is None:
        throttling.enregistrer_echec(identifiant, ip)
        deposer_erreurs(request, email=MESSAGE_ECHEC)
        return redirect("/login")

    # Laravel ouvre la session puis la referme si le compte est désactivé ;
    # le résultat visible est identique et l'ordre n'a pas d'effet de bord ici.
    if compte_desactive(user):
        deposer_erreurs(request, email=MESSAGE_COMPTE_DESACTIVE)
        return redirect("/login")

    throttling.reinitialiser(identifiant, ip)
    ouvrir_session(request, user, backend=BACKEND_AUTH)
    request.session.cycle_key()

    UserLoginLog.objects.create(
        user=user,
        logged_in_at=datetime.now().replace(microsecond=0),
        ip_address=ip or None,
        user_agent=(request.META.get("HTTP_USER_AGENT") or "")[:512] or None,
    )

    suivante = request.session.pop("url_demandee", None)
    return redirect(suivante or "/dashboard")


@csrf_protect
@http_methods("POST")
def logout_store(request):
    fermer_session(request)
    request.session.flush()
    return redirect("/")


@http_methods("GET", "HEAD")
def racine(request):
    return redirect("/dashboard" if request.user.is_authenticated else "/login")


@http_methods("GET", "HEAD")
def manifeste_pwa(request):
    """Manifeste PWA — portage de la route `pwa.manifest` de routes/web.php."""
    icone = request.build_absolute_uri(static("logo/iconesgda.png"))
    nom = settings.APP_NAME

    return JsonResponse(
        {
            "name": nom,
            "short_name": nom,
            "description": "Application de gestion des ventes de cartes et du suivi des performances.",
            "start_url": "/",
            "scope": "/",
            "display": "standalone",
            "display_override": ["standalone", "minimal-ui", "browser"],
            "background_color": "#381419",
            "theme_color": "#FF6A3A",
            "lang": "fr",
            "dir": "ltr",
            "orientation": "any",
            "icons": [
                {"src": icone, "sizes": "192x192", "type": "image/png", "purpose": "any"},
                {"src": icone, "sizes": "512x512", "type": "image/png", "purpose": "any"},
                {"src": icone, "sizes": "512x512", "type": "image/png", "purpose": "maskable"},
            ],
        },
        content_type="application/manifest+json; charset=UTF-8",
        json_dumps_params={"ensure_ascii": False},
    )
