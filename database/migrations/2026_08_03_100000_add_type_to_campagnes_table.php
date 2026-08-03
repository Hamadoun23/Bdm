<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campagnes', function (Blueprint $table) {
            $table->string('type', 30)->default('vente_carte')->after('nom');
        });
    }

    public function down(): void
    {
        Schema::table('campagnes', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
