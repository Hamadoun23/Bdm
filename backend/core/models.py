"""
Modèles du socle : utilisateurs, agences, types de cartes, journal de connexion.

Correspondances Laravel : app/Models/User.php, Agence.php, TypeCarte.php,
UserLoginLog.php.
"""

from django.contrib.auth.base_user import AbstractBaseUser, BaseUserManager
from django.db import models

from .models_base import LaravelModel


class Role(models.TextChoices):
    ADMIN = "admin", "Administrateur"
    DIRECTION = "direction", "Direction"
    COMMERCIAL = "commercial", "Commercial"
    COMMERCIAL_TELEPHONIQUE = "commercial_telephonique", "Commercial téléphonique"


#: Les deux rôles qui signent un contrat de prestation et sont rattachés à une agence.
ROLES_COMMERCIAUX = [Role.COMMERCIAL, Role.COMMERCIAL_TELEPHONIQUE]


class Agence(LaravelModel):
    id = models.BigAutoField(primary_key=True)
    ordre = models.PositiveIntegerField(default=0)
    nom = models.CharField(max_length=255)
    adresse = models.CharField(max_length=255, null=True, blank=True)
    chef = models.ForeignKey(
        "core.User",
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        db_column="chef_id",
        related_name="agences_dirigees",
    )

    class Meta:
        managed = False
        db_table = "agences"

    def __str__(self):
        return self.nom


class TypeCarte(LaravelModel):
    id = models.BigAutoField(primary_key=True)
    code = models.CharField(max_length=50, unique=True)
    actif = models.BooleanField(default=True)

    class Meta:
        managed = False
        db_table = "types_cartes"

    def __str__(self):
        return self.code


class UserManager(BaseUserManager):
    def get_by_natural_key(self, email):
        return self.get(email=email)

    def commerciaux(self):
        """Commerciaux terrain et téléphoniques — le périmètre « commercial » au sens large."""
        return self.filter(role__in=ROLES_COMMERCIAUX)


class User(AbstractBaseUser):
    """
    Mappé sur la table `users` de Laravel.

    Deux écarts assumés par rapport au modèle Django standard :

    - Pas de `PermissionsMixin` : il exigerait les colonnes `is_superuser`,
      `groups` et `user_permissions`, absentes de la table. Les droits reposent
      uniquement sur la colonne `role`, comme dans Laravel.
    - `last_login` est retiré : la colonne n'existe pas. Le suivi des connexions
      passe par la table `user_login_logs` (cf. UserLoginLog).
    """

    last_login = None

    id = models.BigAutoField(primary_key=True)
    name = models.CharField(max_length=255)
    prenom = models.CharField(max_length=100, null=True, blank=True)
    telephone = models.CharField(max_length=20, null=True, blank=True)
    role = models.CharField(max_length=32, choices=Role.choices, default=Role.COMMERCIAL)
    agence = models.ForeignKey(
        Agence,
        on_delete=models.SET_NULL,
        null=True,
        blank=True,
        db_column="agence_id",
        related_name="utilisateurs",
    )
    actif = models.BooleanField(default=True)
    adresse_contrat = models.TextField(null=True, blank=True)
    piece_identite_ref = models.CharField(max_length=191, null=True, blank=True)
    email = models.EmailField(max_length=255, unique=True, null=True, blank=True)
    email_verified_at = models.DateTimeField(null=True, blank=True)
    # Laravel stocke du bcrypt en varchar(255) ; Django plafonne à 128 par défaut.
    password = models.CharField(max_length=255)
    remember_token = models.CharField(max_length=100, null=True, blank=True)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    USERNAME_FIELD = "email"
    REQUIRED_FIELDS = ["name"]

    objects = UserManager()

    class Meta:
        managed = False
        db_table = "users"

    def __str__(self):
        return self.nom_complet

    def save(self, *args, **kwargs):
        from datetime import datetime

        now = datetime.now().replace(microsecond=0)
        if self._state.adding and self.created_at is None:
            self.created_at = now
        self.updated_at = now
        if kwargs.get("update_fields") is not None:
            kwargs["update_fields"] = tuple(set(kwargs["update_fields"]) | {"updated_at"})
        return super().save(*args, **kwargs)

    # -- Compatibilité Django ------------------------------------------------

    @property
    def is_active(self):
        """`actif` de Laravel : un compte désactivé ne peut pas se connecter."""
        return bool(self.actif)

    @property
    def is_anonymous(self):
        return False

    @property
    def is_authenticated(self):
        return True

    # -- Rôles (miroir de app/Models/User.php) -------------------------------

    @property
    def is_admin(self):
        return self.role == Role.ADMIN

    @property
    def is_direction(self):
        return self.role == Role.DIRECTION

    @property
    def is_commercial(self):
        """Commercial terrain (ventes sur le terrain)."""
        return self.role == Role.COMMERCIAL

    @property
    def is_commercial_telephonique(self):
        """Téléopératrice : reporting d'appels, sans tunnel de vente terrain."""
        return self.role == Role.COMMERCIAL_TELEPHONIQUE

    @property
    def is_commercial_ou_telephonique(self):
        return self.role in ROLES_COMMERCIAUX

    @property
    def nom_complet(self):
        return f"{self.prenom} {self.name}".strip() if self.prenom else self.name


class UserLoginLog(LaravelModel):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(
        User, on_delete=models.CASCADE, db_column="user_id", related_name="login_logs"
    )
    logged_in_at = models.DateTimeField()
    ip_address = models.CharField(max_length=45, null=True, blank=True)
    user_agent = models.CharField(max_length=512, null=True, blank=True)

    class Meta:
        managed = False
        db_table = "user_login_logs"
        # `-id` en tri secondaire : plusieurs connexions tombent à la même
        # seconde et MySQL les rend alors dans un ordre arbitraire, qui varie
        # avec le plan d'exécution. Sans ce départage, la pagination n'est pas
        # reproductible d'une requête à l'autre.
        ordering = ["-logged_in_at", "-id"]
