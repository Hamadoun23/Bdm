<?php

use App\Models\Agence;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fusionne l’agence BOULKASSOULBOUGOU vers Senou (ventes, utilisateurs, pivots campagnes).
 */
return new class extends Migration
{
    private const NOM_ANCIEN = 'BOULKASSOULBOUGOU';

    private const NOM_NOUVEAU = 'Senou';

    public function up(): void
    {
        $old = $this->findAgenceByName(self::NOM_ANCIEN);
        if ($old === null) {
            return;
        }

        $senou = $this->findAgenceByName(self::NOM_NOUVEAU);
        if ($senou === null) {
            $maxOrdre = (int) (Agence::query()->max('ordre') ?? 0);
            $senou = Agence::create([
                'nom' => self::NOM_NOUVEAU,
                'ordre' => $maxOrdre + 1,
            ]);
        }

        $oldId = (int) $old->id;
        $senouId = (int) $senou->id;

        if ($oldId === $senouId) {
            return;
        }

        DB::transaction(function () use ($oldId, $senouId) {
            Vente::query()->where('agence_id', $oldId)->update(['agence_id' => $senouId]);

            User::query()->where('agence_id', $oldId)->update(['agence_id' => $senouId]);

            $this->syncCampagneAgencePivot($oldId, $senouId);

            Agence::query()->where('id', $oldId)->update(['chef_id' => null]);

            Agence::query()->where('id', $oldId)->delete();
        });
    }

    public function down(): void
    {
        // Irréversible sans sauvegarde des ids ; no-op.
    }

    private function findAgenceByName(string $nom): ?Agence
    {
        $n = mb_strtolower(trim($nom));

        return Agence::query()
            ->whereRaw('LOWER(TRIM(nom)) = ?', [$n])
            ->first();
    }

    private function syncCampagneAgencePivot(int $oldAgenceId, int $senouAgenceId): void
    {
        $rows = DB::table('campagne_agence')->where('agence_id', $oldAgenceId)->get();
        foreach ($rows as $row) {
            $hasSenou = DB::table('campagne_agence')
                ->where('campagne_id', $row->campagne_id)
                ->where('agence_id', $senouAgenceId)
                ->exists();
            if ($hasSenou) {
                DB::table('campagne_agence')->where('id', $row->id)->delete();
            } else {
                DB::table('campagne_agence')->where('id', $row->id)->update(['agence_id' => $senouAgenceId]);
            }
        }
    }
};
