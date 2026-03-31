<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('primes')->where('rang', 2)->delete();

        Schema::table('campagnes', function (Blueprint $table) {
            $table->dropColumn('prime_top2');
        });

        Schema::table('campagnes', function (Blueprint $table) {
            $table->renameColumn('prime_top1', 'prime_meilleur_vendeur');
        });
    }

    public function down(): void
    {
        Schema::table('campagnes', function (Blueprint $table) {
            $table->renameColumn('prime_meilleur_vendeur', 'prime_top1');
        });

        Schema::table('campagnes', function (Blueprint $table) {
            $table->decimal('prime_top2', 12, 0)->default(15000)->after('prime_top1');
        });
    }
};
