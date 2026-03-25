<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('types_cartes', function (Blueprint $table) {
            $table->dropColumn(['libelle', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::table('types_cartes', function (Blueprint $table) {
            $table->string('libelle', 120)->after('code');
            $table->unsignedSmallInteger('ordre')->default(0)->after('prix');
        });
    }
};
