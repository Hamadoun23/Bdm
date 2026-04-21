<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commercial_agence_transferts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('nouvelle_agence_id')->constrained('agences')->cascadeOnDelete();
            $table->json('snapshots');
            $table->unsignedBigInteger('profil_agence_avant')->nullable();
            $table->unsignedBigInteger('profil_agence_apres')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('profil_agence_avant')->references('id')->on('agences')->nullOnDelete();
            $table->foreign('profil_agence_apres')->references('id')->on('agences')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commercial_agence_transferts');
    }
};
