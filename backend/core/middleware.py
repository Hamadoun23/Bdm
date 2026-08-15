"""
Middlewares transverses.

- `EnsureCompteActifMiddleware` : portage de App\\Http\\Middleware\\EnsureCompteActif.
- `InertiaSharedDataMiddleware` : portage de App\\Http\\Middleware\\HandleInertiaRequests::share().
"""

import json

from django.contrib.auth import logout
from django.http import QueryDict
from django.shortcuts import redirect

from .auth_backend import MESSAGE_COMPTE_DESACTIVE, compte_desactive

#: Clé de session où sont déposées les erreurs de validation entre deux
#: requêtes, à la manière du `withErrors()` de Laravel. Elles ressortent dans
#: la prop Inertia `errors`, que le frontend lit déjà.
CLE_ERREURS = "_inertia_errors"

#: Messages flash (succès, avertissement…), équivalents de `session()->flash()`.
CLE_FLASH = "_inertia_flash"

#: Laravel expose systématiquement ces cinq clés, à `null` quand il n'y a rien.
#: On reproduit cette forme pour que les props soient strictement identiques.
CLES_FLASH = ("success", "error", "warning", "status", "success_article")


def deposer_erreurs(request, **erreurs):
    request.session[CLE_ERREURS] = {
        champ: str(message) for champ, message in erreurs.items()
    }


def retour_avec_erreurs(request, erreurs):
    """
    Équivalent de `back()->withErrors($erreurs)->withInput()`.

    Inertia renvoie l'utilisateur sur la page précédente ; les erreurs sont
    lues par `useForm` via la prop partagée `errors`. Les anciennes valeurs
    n'ont pas besoin d'être renvoyées : le formulaire React conserve son état.
    """
    from django.shortcuts import redirect

    request.session[CLE_ERREURS] = {
        champ: str(message) for champ, message in erreurs.items()
    }
    return redirect(request.META.get("HTTP_REFERER") or "/")


def deposer_flash(request, **messages):
    flash = request.session.get(CLE_FLASH, {})
    flash.update({cle: str(valeur) for cle, valeur in messages.items()})
    request.session[CLE_FLASH] = flash


class CorpsJsonMiddleware:
    """
    Rend les corps JSON lisibles via `request.POST`.

    Inertia envoie les formulaires en `application/json` dès qu'ils ne
    contiennent aucun fichier. Django, lui, ne remplit `request.POST` que pour
    les corps `form-urlencoded` et `multipart` : sans ce middleware, tous les
    champs arrivent vides et chaque formulaire échoue silencieusement.

    Les objets imbriqués sont aplatis en clés pointées (`propose.3`), comme le
    fait `$request->input('propose.3')` côté Laravel.

    Ce middleware doit précéder `CsrfViewMiddleware` : celui-ci lit `request.POST`
    pour y chercher `csrfmiddlewaretoken`, et toute lecture du flux avant nous
    rendrait `request.body` inaccessible.
    """

    #: Méthodes dont le corps peut porter des données de formulaire.
    METHODES = ("POST", "PUT", "PATCH", "DELETE")

    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        if request.method in self.METHODES:
            type_contenu = (request.content_type or "").lower()

            if type_contenu == "application/json" and request.body:
                self._remplacer_post(request, self._depuis_json(request.body))
            elif (
                request.method != "POST"
                and type_contenu == "application/x-www-form-urlencoded"
            ):
                # Django ne décode le corps que pour les POST ; les PUT et PATCH
                # d'Inertia doivent l'être aussi.
                self._remplacer_post(request, QueryDict(request.body))

        return self.get_response(request)

    def _remplacer_post(self, request, donnees):
        from django.utils.datastructures import MultiValueDict

        request._post = donnees
        request._files = MultiValueDict()

    def _depuis_json(self, corps):
        try:
            donnees = json.loads(corps)
        except (ValueError, UnicodeDecodeError):
            return QueryDict(mutable=True)

        parametres = QueryDict(mutable=True)
        if not isinstance(donnees, dict):
            return parametres

        for cle, valeur in donnees.items():
            if isinstance(valeur, (list, tuple)):
                parametres.setlist(cle, [self._texte(v) for v in valeur])
            elif isinstance(valeur, dict):
                for sous_cle, sous_valeur in valeur.items():
                    parametres[f"{cle}.{sous_cle}"] = self._texte(sous_valeur)
            else:
                parametres[cle] = self._texte(valeur)
        return parametres

    def _texte(self, valeur):
        """Ramène la valeur JSON à ce qu'un formulaire HTML aurait transmis."""
        if valeur is None:
            return ""
        if isinstance(valeur, bool):
            # Une case cochée vaut « 1 », une case décochée n'est pas envoyée.
            return "1" if valeur else ""
        return str(valeur)


class EnsureCompteActifMiddleware:
    """Déconnecte un compte désactivé et renvoie vers la page de connexion."""

    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        user = getattr(request, "user", None)
        if user is not None and user.is_authenticated and compte_desactive(user):
            logout(request)
            request.session.flush()
            request.session[CLE_ERREURS] = {"email": MESSAGE_COMPTE_DESACTIVE}
            return redirect("/login")
        return self.get_response(request)


class InertiaSharedDataMiddleware:
    """
    Props partagées avec toutes les pages Inertia : utilisateur courant,
    messages flash et erreurs de validation.

    `peut_vendre` / `peut_enroler` conditionnent l'affichage des tunnels de
    saisie côté React : un commercial ne peut saisir que s'il est réellement
    engagé sur une campagne ouverte du type correspondant.
    """

    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        from inertia import share

        share(
            request,
            auth=lambda: {"user": self._utilisateur(request)},
            flash=lambda: self._flash(request),
            errors=lambda: request.session.pop(CLE_ERREURS, {}) or {},
        )
        return self.get_response(request)

    def _flash(self, request):
        depose = request.session.pop(CLE_FLASH, {}) or {}
        return {cle: depose.get(cle) for cle in CLES_FLASH}

    def _utilisateur(self, request):
        user = getattr(request, "user", None)
        if user is None or not user.is_authenticated:
            return None

        return {
            "id": user.id,
            "name": user.name,
            "prenom": user.prenom,
            "role": user.role,
            "agence_id": user.agence_id,
            "is_admin": user.is_admin,
            "is_direction": user.is_direction,
            "is_commercial": user.is_commercial,
            "is_commercial_telephonique": user.is_commercial_telephonique,
            "peut_vendre": self._campagne_ouverte_engagee(user, "vente_carte"),
            "peut_enroler": self._campagne_ouverte_engagee(user, "enrolement_app"),
        }

    def _campagne_ouverte_engagee(self, user, type_campagne) -> bool:
        """Le commercial a-t-il une campagne ouverte de ce type où il est engagé ?"""
        if not user.is_commercial or not user.agence_id:
            return False

        from campagnes.models import Campagne

        return any(
            campagne.est_engage_commercial(user.id)
            for campagne in Campagne.actives_pour_agence(int(user.agence_id)).filter(
                type=type_campagne
            )
        )
