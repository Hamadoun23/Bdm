<?php

use App\Models\Agence;
use App\Models\Campagne;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Agences réservées à la2è vague : exclues du périmètre de « Campagne Avril 2026 ». */
    private const AGENCES_DEUXIEME_VAGUE = [
        'SEMA GESCO',
        'MISSIRA',
        'QUINZAMBOUGOU',
        'SEBENIKORO',
        'HAMDALLAYE',
        'LAFIABOUGOU',
        'TOROKOROBOUGOU',
        'MAGNAMBOUGOU',
        'AZAR',
        'Senou',
        'KATI',
    ];

    public function up(): void
    {
        Schema::table('agences', function (Blueprint $table) {
            $table->unsignedInteger('ordre')->default(0)->after('id');
        });

        foreach (Agence::query()->orderBy('id')->cursor() as $agence) {
            $agence->update(['ordre' => (int) $agence->id]);
        }

        $campagne = Campagne::query()->where('nom', 'Campagne Avril 2026')->first();
        if ($campagne) {
            $campagne->update(['toutes_agences' => false]);

            $ids = Agence::query()
                ->whereNotIn('nom', self::AGENCES_DEUXIEME_VAGUE)
                ->orderBy('ordre')
                ->orderBy('nom')
                ->pluck('id')
                ->all();

            $campagne->agences()->sync($ids);
        }
    }

    public function down(): void
    {
        Schema::table('agences', function (Blueprint $table) {
            $table->dropColumn('ordre');
        });
    }
};
