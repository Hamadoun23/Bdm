<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropColumn('montant');
        });

        Schema::table('types_cartes', function (Blueprint $table) {
            $table->dropColumn('prix');
        });
    }

    public function down(): void
    {
        Schema::table('types_cartes', function (Blueprint $table) {
            $table->unsignedBigInteger('prix')->default(0)->after('code');
        });

        Schema::table('ventes', function (Blueprint $table) {
            $table->unsignedBigInteger('montant')->default(0)->after('type_carte_id');
        });
    }
};
