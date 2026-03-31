<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('adresse_contrat')->nullable()->after('actif');
            $table->string('piece_identite_ref', 191)->nullable()->after('adresse_contrat');
        });

        Schema::table('campagnes', function (Blueprint $table) {
            $table->boolean('contrat_tous_commerciaux')->default(true)->after('aide_hebdo_tous_commerciaux');
            $table->unsignedInteger('contrat_emolument_forfait')->default(50000)->after('contrat_tous_commerciaux');
            $table->unsignedInteger('contrat_forfait_communication')->default(2000)->after('contrat_emolument_forfait');
            $table->unsignedInteger('contrat_forfait_deplacement')->default(3000)->after('contrat_forfait_communication');
            $table->string('contrat_representant_nom', 191)->default('Yaya H DIALLO')->after('contrat_forfait_deplacement');
            $table->string('contrat_lieu_signature', 191)->default('Bamako')->after('contrat_representant_nom');
            $table->text('contrat_clause_libre')->nullable()->after('contrat_lieu_signature');
            $table->timestamp('contrat_publie_at')->nullable()->after('contrat_clause_libre');
        });

        Schema::create('campagne_commercial_contrat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['campagne_id', 'user_id']);
        });

        Schema::create('contrat_prestation_reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('statut', 32)->default('en_attente'); // en_attente, accepte, rejete
            $table->timestamp('repondu_at')->nullable();
            $table->timestamps();
            $table->unique(['campagne_id', 'user_id']);
        });

        Schema::create('campagne_aide_versements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('semaine_debut');
            $table->unsignedInteger('montant_carburant')->default(0);
            $table->unsignedInteger('montant_credit_tel')->default(0);
            $table->foreignId('enregistre_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('accuse_at')->nullable();
            $table->text('accuse_commentaire')->nullable();
            $table->timestamps();
            $table->index(['campagne_id', 'user_id', 'semaine_debut']);
        });

        DB::table('campagnes')->update(['contrat_tous_commerciaux' => DB::raw('aide_hebdo_tous_commerciaux')]);

        foreach (DB::table('campagne_aide_beneficiaire')->get() as $row) {
            DB::table('campagne_commercial_contrat')->insertOrIgnore([
                'campagne_id' => $row->campagne_id,
                'user_id' => $row->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('campagne_aide_versements');
        Schema::dropIfExists('contrat_prestation_reponses');
        Schema::dropIfExists('campagne_commercial_contrat');

        Schema::table('campagnes', function (Blueprint $table) {
            $table->dropColumn([
                'contrat_tous_commerciaux', 'contrat_emolument_forfait', 'contrat_forfait_communication',
                'contrat_forfait_deplacement', 'contrat_representant_nom', 'contrat_lieu_signature',
                'contrat_clause_libre', 'contrat_publie_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['adresse_contrat', 'piece_identite_ref']);
        });
    }
};
