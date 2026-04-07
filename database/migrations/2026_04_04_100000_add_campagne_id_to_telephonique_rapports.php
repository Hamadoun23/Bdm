<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('telephonique_rapports', function (Blueprint $table) {
            $table->foreignId('campagne_id')->nullable()->after('user_id')->constrained('campagnes')->nullOnDelete();
            $table->index(['campagne_id', 'date_rapport']);
        });
    }

    public function down(): void
    {
        Schema::table('telephonique_rapports', function (Blueprint $table) {
            $table->dropForeign(['campagne_id']);
        });
    }
};
