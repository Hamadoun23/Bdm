<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('telephonique_rapports', function (Blueprint $table) {
            $table->json('cartes_proposees')->nullable()->after('clients_deja_servis_pct');
        });
    }

    public function down(): void
    {
        Schema::table('telephonique_rapports', function (Blueprint $table) {
            $table->dropColumn('cartes_proposees');
        });
    }
};
