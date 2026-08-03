<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Schéma final consolidé, pour Postgres uniquement (stack Docker locale).
 *
 * Les 33 migrations historiques (2025-03 → 2026-04) rejouent pas à pas
 * l'évolution du schéma sur MySQL — certaines contiennent du SQL brut
 * MySQL-only (voir 2025_03_24_000000_create_types_cartes_and_migrate.php)
 * ou des migrations de données ponctuelles sans intérêt pour une base
 * Postgres fraîche. Plutôt que de les rendre toutes portables, cette
 * migration recrée directement l'état final du schéma (vérifié via
 * `SHOW CREATE TABLE` sur la base MySQL de dev le 2026-07-30), et ne
 * s'exécute que sur le driver pgsql — elle est un no-op sur mysql/sqlite,
 * où les migrations historiques restent la source de vérité.
 *
 * Colonnes ENUM MySQL → string + validation applicative (Rule::in), pour
 * rester cohérent avec le pattern déjà utilisé sur `types_cartes` (ENUM
 * figé remplacé par une table de référence flexible).
 * Colonnes JSON (stockées en longtext+CHECK côté MySQL) → jsonb.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        // La plupart des migrations historiques (create_agences_table,
        // create_clients_table, create_campagnes_table, enhance_campagnes_table,
        // contrats_prestation_aides_versements, campagne_contrat_articles,
        // create_commercial_agence_transferts_table, add_remise_types_cartes...)
        // ne sont PAS driver-guardées : ce sont de simples appels Blueprint,
        // donc elles s'exécutent aussi sur Postgres et créent déjà ces tables
        // (dans une forme intermédiaire, pas la forme finale) avant que cette
        // migration ne s'exécute. On repart d'une ardoise propre ici, dans
        // l'ordre inverse des dépendances de clé étrangère.
        Schema::dropIfExists('ventes');
        Schema::dropIfExists('user_login_logs');
        Schema::dropIfExists('telephonique_rapports');
        Schema::dropIfExists('reclamations');
        Schema::dropIfExists('primes');
        Schema::dropIfExists('contrat_prestation_reponses');
        Schema::dropIfExists('commercial_agence_transferts');
        Schema::dropIfExists('campagne_remise_type_carte');
        Schema::dropIfExists('campagne_contrat_articles');
        Schema::dropIfExists('campagne_commercial_contrat');
        Schema::dropIfExists('campagne_aide_versements');
        Schema::dropIfExists('campagne_aide_beneficiaire');
        Schema::dropIfExists('campagne_actions');
        Schema::dropIfExists('campagne_agence');
        Schema::dropIfExists('campagnes');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('mouvements_stock');
        if (Schema::hasTable('agences') && Schema::hasColumn('agences', 'chef_id')) {
            Schema::table('agences', function (Blueprint $table) {
                $table->dropForeign(['chef_id']);
            });
        }
        Schema::dropIfExists('users');
        Schema::dropIfExists('agences');
        Schema::dropIfExists('types_cartes');

        Schema::create('types_cartes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('agences', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ordre')->default(0);
            $table->string('nom');
            $table->string('adresse')->nullable();
            // FK vers users ajoutée plus bas (dépendance circulaire agences <-> users).
            $table->unsignedBigInteger('chef_id')->nullable();
            $table->timestamps();
        });

        // La migration socle 0001_01_01_000000 crée déjà `users` (id, name,
        // email, email_verified_at, password, remember_token, timestamps).
        // On la recrée ici avec la forme finale complète (plus simple et
        // plus sûr que d'enchaîner des ->change() successifs).
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('prenom', 100)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('role', 30)->default('commercial');
            $table->foreignId('agence_id')->nullable()->constrained('agences')->nullOnDelete();
            $table->boolean('actif')->default(true);
            $table->text('adresse_contrat')->nullable();
            $table->string('piece_identite_ref', 191)->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('agences', function (Blueprint $table) {
            $table->foreign('chef_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_carte_id')->constrained('types_cartes');
            $table->string('prenom');
            $table->string('nom');
            $table->string('telephone', 20)->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('quartier', 100)->nullable();
            $table->string('statut_carte', 20)->default('vendue');
            $table->string('carte_identite')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('campagnes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('prime_meilleur_vendeur', 12, 0)->default(25000);
            $table->decimal('remise_pourcentage', 5, 2)->nullable();
            $table->boolean('remise_tous_types_cartes')->default(true);
            $table->boolean('aide_hebdo_active')->default(false);
            $table->unsignedInteger('aide_hebdo_montant')->default(5000);
            $table->unsignedInteger('aide_hebdo_carburant')->default(3000);
            $table->unsignedInteger('aide_hebdo_credit_tel')->default(2000);
            $table->boolean('aide_hebdo_tous_commerciaux')->default(true);
            $table->boolean('contrat_tous_commerciaux')->default(true);
            $table->unsignedInteger('contrat_emolument_forfait')->default(50000);
            $table->unsignedInteger('contrat_forfait_communication')->default(2000);
            $table->unsignedInteger('contrat_forfait_deplacement')->default(3000);
            $table->string('contrat_representant_nom', 191)->default('Yaya H DIALLO');
            $table->string('contrat_lieu_signature', 191)->default('Bamako');
            $table->text('contrat_clause_libre')->nullable();
            $table->timestamp('contrat_publie_at')->nullable();
            $table->boolean('actif')->default(true);
            $table->string('statut', 20)->default('programmee');
            $table->boolean('toutes_agences')->default(true);
            $table->timestamps();
        });

        Schema::create('campagne_agence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes')->cascadeOnDelete();
            $table->foreignId('agence_id')->constrained('agences')->cascadeOnDelete();
            $table->unique(['campagne_id', 'agence_id']);
        });

        Schema::create('campagne_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes')->cascadeOnDelete();
            $table->string('action');
            $table->text('description');
            $table->jsonb('donnees_avant')->nullable();
            $table->jsonb('donnees_apres')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('campagne_aide_beneficiaire', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['campagne_id', 'user_id']);
        });

        Schema::create('campagne_aide_versements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('semaine_debut');
            $table->unsignedInteger('montant_carburant')->default(0);
            $table->unsignedInteger('montant_credit_tel')->default(0);
            $table->foreignId('enregistre_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('accuse_at')->nullable();
            $table->text('accuse_commentaire')->nullable();
            $table->timestamps();
            $table->index(['campagne_id', 'user_id', 'semaine_debut']);
        });

        Schema::create('campagne_commercial_contrat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['campagne_id', 'user_id']);
        });

        Schema::create('campagne_contrat_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes')->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('titre');
            $table->text('contenu');
            $table->timestamps();
        });

        Schema::create('campagne_remise_type_carte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes')->cascadeOnDelete();
            $table->foreignId('type_carte_id')->constrained('types_cartes')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['campagne_id', 'type_carte_id']);
        });

        Schema::create('commercial_agence_transferts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('nouvelle_agence_id')->constrained('agences')->cascadeOnDelete();
            $table->jsonb('snapshots');
            $table->foreignId('profil_agence_avant')->nullable()->constrained('agences')->nullOnDelete();
            $table->foreignId('profil_agence_apres')->nullable()->constrained('agences')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('contrat_prestation_reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('statut', 32)->default('en_attente');
            $table->timestamp('repondu_at')->nullable();
            $table->timestamps();
            $table->unique(['campagne_id', 'user_id']);
        });

        Schema::create('primes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('periode', 7);
            $table->decimal('montant', 12, 0);
            $table->integer('rang');
            $table->timestamps();
        });

        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 30);
            $table->string('statut', 20)->default('ouvert');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('telephonique_rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('campagne_id')->nullable()->constrained('campagnes')->nullOnDelete();
            $table->date('date_rapport');
            $table->unsignedInteger('appels_emis')->default(0);
            $table->unsignedInteger('appels_joignables')->default(0);
            $table->unsignedInteger('appels_non_joignables')->default(0);
            $table->decimal('taux_joignabilite', 6, 2)->nullable();
            $table->unsignedInteger('clients_interesses_nombre')->default(0);
            $table->decimal('clients_interesses_pct', 6, 2)->nullable();
            $table->unsignedInteger('clients_deja_servis_nombre')->default(0);
            $table->decimal('clients_deja_servis_pct', 6, 2)->nullable();
            $table->jsonb('cartes_proposees')->nullable();
            $table->unsignedInteger('propose_visa')->default(0);
            $table->unsignedInteger('propose_gim')->default(0);
            $table->unsignedInteger('propose_cauris')->default(0);
            $table->unsignedInteger('propose_prepayee')->default(0);
            $table->unsignedInteger('nj_repondeur')->default(0);
            $table->unsignedInteger('nj_numero_errone')->default(0);
            $table->unsignedInteger('nj_hors_reseau')->default(0);
            $table->unsignedInteger('nj_autres_nombre')->default(0);
            $table->string('nj_autres_precision')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'date_rapport']);
            $table->index(['campagne_id', 'date_rapport']);
        });

        Schema::create('user_login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('logged_in_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();
            $table->index(['user_id', 'logged_in_at']);
        });

        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_carte_id')->constrained('types_cartes');
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agence_id')->constrained('agences')->cascadeOnDelete();
            $table->foreignId('campagne_id')->nullable()->constrained('campagnes')->nullOnDelete();
            $table->string('statut_activation', 20)->default('vendue');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        // Recovery attendue en local : `migrate:fresh`, pas un rollback pas à
        // pas — on se contente donc de tout redéfaire proprement dans le bon
        // ordre plutôt que de restaurer la forme intermédiaire pré-migration.
        Schema::dropIfExists('ventes');
        Schema::dropIfExists('user_login_logs');
        Schema::dropIfExists('telephonique_rapports');
        Schema::dropIfExists('reclamations');
        Schema::dropIfExists('primes');
        Schema::dropIfExists('contrat_prestation_reponses');
        Schema::dropIfExists('commercial_agence_transferts');
        Schema::dropIfExists('campagne_remise_type_carte');
        Schema::dropIfExists('campagne_contrat_articles');
        Schema::dropIfExists('campagne_commercial_contrat');
        Schema::dropIfExists('campagne_aide_versements');
        Schema::dropIfExists('campagne_aide_beneficiaire');
        Schema::dropIfExists('campagne_actions');
        Schema::dropIfExists('campagne_agence');
        Schema::dropIfExists('campagnes');
        Schema::dropIfExists('clients');

        Schema::table('agences', function (Blueprint $table) {
            $table->dropForeign(['chef_id']);
        });

        Schema::dropIfExists('users');
        Schema::dropIfExists('agences');
        Schema::dropIfExists('types_cartes');

        // Rétablit la forme de base attendue par 0001_01_01_000000_create_users_table::down().
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
