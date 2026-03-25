<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('actif')->default(true)->after('agence_id');
        });

        Schema::table('campagnes', function (Blueprint $table) {
            $table->decimal('remise_pourcentage', 5, 2)->nullable()->after('prime_top2');
            $table->boolean('aide_hebdo_active')->default(false)->after('remise_pourcentage');
            $table->unsignedInteger('aide_hebdo_montant')->default(5000)->after('aide_hebdo_active');
            $table->unsignedInteger('aide_hebdo_carburant')->default(3000)->after('aide_hebdo_montant');
            $table->unsignedInteger('aide_hebdo_credit_tel')->default(2000)->after('aide_hebdo_carburant');
            $table->boolean('aide_hebdo_tous_commerciaux')->default(true)->after('aide_hebdo_credit_tel');
        });

        Schema::create('campagne_aide_beneficiaire', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['campagne_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campagne_aide_beneficiaire');

        Schema::table('campagnes', function (Blueprint $table) {
            $table->dropColumn([
                'remise_pourcentage',
                'aide_hebdo_active',
                'aide_hebdo_montant',
                'aide_hebdo_carburant',
                'aide_hebdo_credit_tel',
                'aide_hebdo_tous_commerciaux',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('actif');
        });
    }
};
