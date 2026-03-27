<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campagnes', function (Blueprint $table) {
            $table->boolean('remise_tous_types_cartes')->default(true)->after('remise_pourcentage');
        });

        Schema::create('campagne_remise_type_carte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained()->cascadeOnDelete();
            $table->foreignId('type_carte_id')->constrained('types_cartes')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['campagne_id', 'type_carte_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campagne_remise_type_carte');

        Schema::table('campagnes', function (Blueprint $table) {
            $table->dropColumn('remise_tous_types_cartes');
        });
    }
};
