"""
Table `demandes_clients_existants` : ventes à un client déjà enregistré.

Depuis le 07/10/2026, un commercial ne peut plus enregistrer directement une
vente pour un client déjà présent dans la base : sa saisie devient une demande
que l'administrateur valide (la vente est alors créée) ou refuse. Les données
saisies sont conservées telles quelles dans `donnees` pour créer la vente à
l'identique au moment de la validation.

Opération purement additive et réversible, comme les autres migrations du
schéma hérité de Laravel.
"""

from django.db import migrations


class Migration(migrations.Migration):

    dependencies = [("terrain", "0001_enrolement_numero_compte")]

    operations = [
        migrations.RunSQL(
            sql="""
            CREATE TABLE `demandes_clients_existants` (
              `id` bigint unsigned NOT NULL AUTO_INCREMENT,
              `user_id` bigint unsigned NOT NULL,
              `campagne_id` bigint unsigned DEFAULT NULL,
              `type_carte_id` bigint unsigned NOT NULL,
              `client_existant_id` bigint unsigned DEFAULT NULL,
              `telephone` varchar(25) NOT NULL,
              `prenom` varchar(255) NOT NULL,
              `nom` varchar(255) NOT NULL,
              `donnees` json NOT NULL,
              `meme_type_carte` tinyint(1) NOT NULL DEFAULT 0,
              `motif` text DEFAULT NULL,
              `statut` varchar(20) NOT NULL DEFAULT 'en_attente',
              `traite_par_id` bigint unsigned DEFAULT NULL,
              `traite_le` timestamp NULL DEFAULT NULL,
              `commentaire_admin` text DEFAULT NULL,
              `vente_id` bigint unsigned DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `demandes_clients_existants_statut_index` (`statut`),
              KEY `demandes_clients_existants_user_id_foreign` (`user_id`),
              KEY `demandes_clients_existants_campagne_id_foreign` (`campagne_id`),
              KEY `demandes_clients_existants_type_carte_id_foreign` (`type_carte_id`),
              KEY `demandes_clients_existants_client_existant_id_foreign` (`client_existant_id`),
              KEY `demandes_clients_existants_traite_par_id_foreign` (`traite_par_id`),
              KEY `demandes_clients_existants_vente_id_foreign` (`vente_id`),
              CONSTRAINT `demandes_clients_existants_user_id_foreign`
                FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
              CONSTRAINT `demandes_clients_existants_campagne_id_foreign`
                FOREIGN KEY (`campagne_id`) REFERENCES `campagnes` (`id`) ON DELETE SET NULL,
              CONSTRAINT `demandes_clients_existants_type_carte_id_foreign`
                FOREIGN KEY (`type_carte_id`) REFERENCES `types_cartes` (`id`) ON DELETE RESTRICT,
              CONSTRAINT `demandes_clients_existants_client_existant_id_foreign`
                FOREIGN KEY (`client_existant_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
              CONSTRAINT `demandes_clients_existants_traite_par_id_foreign`
                FOREIGN KEY (`traite_par_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
              CONSTRAINT `demandes_clients_existants_vente_id_foreign`
                FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            """,
            reverse_sql="DROP TABLE `demandes_clients_existants`;",
        ),
    ]
