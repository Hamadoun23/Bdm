<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campagnes', function (Blueprint $table) {
            $table->string('statut', 20)->default('programmee')->after('actif');
            $table->boolean('toutes_agences')->default(true)->after('statut');
        });

        Schema::create('campagne_agence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agence_id')->constrained()->cascadeOnDelete();
            $table->unique(['campagne_id', 'agence_id']);
        });

        Schema::create('campagne_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained()->cascadeOnDelete();
            $table->string('action'); // arreter, annuler, reprogrammer
            $table->text('description');
            $table->json('donnees_avant')->nullable();
            $table->json('donnees_apres')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campagne_actions');
        Schema::dropIfExists('campagne_agence');
        Schema::table('campagnes', function (Blueprint $table) {
            $table->dropColumn(['statut', 'toutes_agences']);
        });
    }
};
