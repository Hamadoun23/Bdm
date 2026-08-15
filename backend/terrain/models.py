"""
Modèles du terrain : clients, ventes, enrôlements, reporting téléphonique,
primes et réclamations.

Portage de app/Models/{Client,Vente,EnrolementClient,TelephoniqueRapport,
Prime,Reclamation}.php.
"""

from datetime import datetime, timedelta

from django.db import models

from campagnes.models import Campagne
from core.models import Agence, TypeCarte, User
from core.models_base import LaravelModel

#: Fenêtre pendant laquelle un commercial peut encore corriger ou retirer sa
#: saisie. Identique pour les clients, les ventes, les enrôlements et les
#: fiches téléphoniques.
DELAI_MODIFICATION_COMMERCIAL_HEURES = 48


def _dans_le_delai(created_at) -> bool:
    if not isinstance(created_at, datetime):
        return False
    limite = datetime.now() - timedelta(hours=DELAI_MODIFICATION_COMMERCIAL_HEURES)
    return created_at > limite


class StatutCarte(models.TextChoices):
    VENDUE = "vendue", "Vendue"
    ACTIVEE = "activée", "Activée"
    EN_ERREUR = "en_erreur", "En erreur"


class Client(LaravelModel):
    id = models.BigAutoField(primary_key=True)
    type_carte = models.ForeignKey(
        TypeCarte, on_delete=models.RESTRICT, db_column="type_carte_id",
        related_name="clients",
    )
    prenom = models.CharField(max_length=255)
    nom = models.CharField(max_length=255)
    telephone = models.CharField(max_length=20, null=True, blank=True)
    ville = models.CharField(max_length=100, null=True, blank=True)
    quartier = models.CharField(max_length=100, null=True, blank=True)
    statut_carte = models.CharField(
        max_length=20, choices=StatutCarte.choices, default=StatutCarte.VENDUE
    )
    carte_identite = models.CharField(max_length=255, null=True, blank=True)
    user = models.ForeignKey(
        User, on_delete=models.CASCADE, db_column="user_id", related_name="clients"
    )

    class Meta:
        managed = False
        db_table = "clients"

    def __str__(self):
        return f"{self.prenom} {self.nom}".strip()

    @property
    def nom_complet(self):
        return f"{self.prenom} {self.nom}".strip()

    def peut_etre_modifie_ou_supprime_par_commercial(self) -> bool:
        return _dans_le_delai(self.created_at)


class Vente(LaravelModel):
    id = models.BigAutoField(primary_key=True)
    type_carte = models.ForeignKey(
        TypeCarte, on_delete=models.RESTRICT, db_column="type_carte_id",
        related_name="ventes",
    )
    client = models.ForeignKey(
        Client, on_delete=models.CASCADE, db_column="client_id", related_name="ventes"
    )
    user = models.ForeignKey(
        User, on_delete=models.CASCADE, db_column="user_id", related_name="ventes"
    )
    agence = models.ForeignKey(
        Agence, on_delete=models.CASCADE, db_column="agence_id", related_name="ventes"
    )
    campagne = models.ForeignKey(
        Campagne, on_delete=models.SET_NULL, null=True, blank=True,
        db_column="campagne_id", related_name="ventes",
    )
    statut_activation = models.CharField(
        max_length=20, choices=StatutCarte.choices, default=StatutCarte.VENDUE
    )

    class Meta:
        managed = False
        db_table = "ventes"

    def peut_etre_supprimee_par_commercial(self) -> bool:
        return _dans_le_delai(self.created_at)


class EnrolementClient(LaravelModel):
    id = models.BigAutoField(primary_key=True)
    campagne = models.ForeignKey(
        Campagne, on_delete=models.CASCADE, db_column="campagne_id",
        related_name="enrolements",
    )
    user = models.ForeignKey(
        User, on_delete=models.CASCADE, db_column="user_id", related_name="enrolements"
    )
    agence = models.ForeignKey(
        Agence, on_delete=models.CASCADE, db_column="agence_id",
        related_name="enrolements",
    )
    nom = models.CharField(max_length=255)
    prenom = models.CharField(max_length=255)
    telephone = models.CharField(max_length=20, null=True, blank=True)
    adresse = models.CharField(max_length=255, null=True, blank=True)

    class Meta:
        managed = False
        db_table = "enrolement_clients"

    @property
    def nom_complet(self):
        return f"{self.prenom} {self.nom}".strip()

    def peut_etre_modifie_ou_supprime_par_commercial(self) -> bool:
        return _dans_le_delai(self.created_at)


class TelephoniqueRapport(LaravelModel):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(
        User, on_delete=models.CASCADE, db_column="user_id",
        related_name="telephonique_rapports",
    )
    campagne = models.ForeignKey(
        Campagne, on_delete=models.SET_NULL, null=True, blank=True,
        db_column="campagne_id", related_name="telephonique_rapports",
    )
    date_rapport = models.DateField()

    appels_emis = models.PositiveIntegerField(default=0)
    appels_joignables = models.PositiveIntegerField(default=0)
    appels_non_joignables = models.PositiveIntegerField(default=0)
    taux_joignabilite = models.DecimalField(
        max_digits=6, decimal_places=2, null=True, blank=True
    )

    clients_interesses_nombre = models.PositiveIntegerField(default=0)
    clients_interesses_pct = models.DecimalField(
        max_digits=6, decimal_places=2, null=True, blank=True
    )
    clients_deja_servis_nombre = models.PositiveIntegerField(default=0)
    clients_deja_servis_pct = models.DecimalField(
        max_digits=6, decimal_places=2, null=True, blank=True
    )

    #: Dictionnaire {id_type_carte: quantité}. Les colonnes propose_* qui
    #: suivent sont l'ancien format, conservé pour l'historique.
    cartes_proposees = models.JSONField(null=True, blank=True)
    propose_visa = models.PositiveIntegerField(default=0)
    propose_gim = models.PositiveIntegerField(default=0)
    propose_cauris = models.PositiveIntegerField(default=0)
    propose_prepayee = models.PositiveIntegerField(default=0)

    nj_repondeur = models.PositiveIntegerField(default=0)
    nj_numero_errone = models.PositiveIntegerField(default=0)
    nj_hors_reseau = models.PositiveIntegerField(default=0)
    nj_autres_nombre = models.PositiveIntegerField(default=0)
    nj_autres_precision = models.CharField(max_length=255, null=True, blank=True)

    class Meta:
        managed = False
        db_table = "telephonique_rapports"
        unique_together = [("user", "date_rapport")]

    def peut_etre_modifie_ou_supprime(self) -> bool:
        return _dans_le_delai(self.created_at)

    def nombre_propose_pour_type(self, type_carte_id: int) -> int:
        cartes = self.cartes_proposees
        if not isinstance(cartes, dict) or not cartes:
            return 0
        # Les clés peuvent être numériques ou textuelles selon l'ancienneté de la fiche.
        return int(cartes.get(str(type_carte_id), cartes.get(type_carte_id, 0)) or 0)

    def somme_nj_motifs(self) -> int:
        return (
            int(self.nj_repondeur)
            + int(self.nj_numero_errone)
            + int(self.nj_hors_reseau)
            + int(self.nj_autres_nombre)
        )

    def nj_analyse_coherente(self) -> bool:
        return self.somme_nj_motifs() <= int(self.appels_non_joignables)

    def pct_interesses_calcule(self):
        """% dérivé des appels émis, pour l'affichage quand la colonne est nulle."""
        if self.appels_emis <= 0:
            return None
        return round(self.clients_interesses_nombre / self.appels_emis * 100, 2)

    def pct_deja_servis_calcule(self):
        if self.appels_emis <= 0:
            return None
        return round(self.clients_deja_servis_nombre / self.appels_emis * 100, 2)

    def resume_cartes_proposees(self) -> str:
        """Résumé lisible pour les listes admin, ex. « VISA: 2, GIM: 1 »."""
        cartes = self.cartes_proposees
        if not isinstance(cartes, dict) or not cartes:
            return "—"
        types = {
            t.id: t.code
            for t in TypeCarte.objects.filter(id__in=[int(k) for k in cartes.keys()])
        }
        parties = [
            f"{types.get(int(id_type), f'#{id_type}')}: {nombre}"
            for id_type, nombre in cartes.items()
            if int(nombre or 0) > 0
        ]
        return ", ".join(parties) if parties else "—"


class Prime(LaravelModel):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(
        User, on_delete=models.CASCADE, db_column="user_id", related_name="primes"
    )
    #: Période au format « AAAA-MM ».
    periode = models.CharField(max_length=7)
    montant = models.DecimalField(max_digits=12, decimal_places=0)
    rang = models.IntegerField()

    class Meta:
        managed = False
        db_table = "primes"


class TypeReclamation(models.TextChoices):
    ACTIVATION = "activation", "Activation"
    MOT_DE_PASSE = "mot_de_passe", "Mot de passe"
    RECHARGEMENT = "rechargement", "Rechargement"


class StatutReclamation(models.TextChoices):
    OUVERT = "ouvert", "Ouvert"
    EN_COURS = "en_cours", "En cours"
    RESOLU = "resolu", "Résolu"


class Reclamation(LaravelModel):
    id = models.BigAutoField(primary_key=True)
    client = models.ForeignKey(
        Client, on_delete=models.CASCADE, db_column="client_id",
        related_name="reclamations",
    )
    user = models.ForeignKey(
        User, on_delete=models.CASCADE, db_column="user_id",
        related_name="reclamations",
    )
    type = models.CharField(max_length=20, choices=TypeReclamation.choices)
    statut = models.CharField(
        max_length=20, choices=StatutReclamation.choices, default=StatutReclamation.OUVERT
    )
    description = models.TextField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "reclamations"
