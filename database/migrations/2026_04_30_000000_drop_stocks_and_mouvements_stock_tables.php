<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('mouvements_stock');
        Schema::dropIfExists('stocks');
    }

    public function down(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_carte_id')->constrained('types_cartes')->cascadeOnDelete();
            $table->integer('quantite')->default(0);
            $table->foreignId('agence_id')->constrained('agences')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['type_carte_id', 'agence_id']);
        });

        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agence_id')->constrained('agences')->cascadeOnDelete();
            $table->foreignId('type_carte_id')->constrained('types_cartes')->cascadeOnDelete();
            $table->integer('quantite');
            $table->enum('type_mouvement', ['vente', 'entree', 'ajustement']);
            $table->foreignId('vente_id')->nullable()->constrained('ventes')->nullOnDelete();
            $table->timestamps();
        });
    }
};
