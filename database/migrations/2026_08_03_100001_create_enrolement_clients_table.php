<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrolement_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agence_id')->constrained('agences')->cascadeOnDelete();
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone', 20)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->timestamps();

            $table->index(['campagne_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrolement_clients');
    }
};
