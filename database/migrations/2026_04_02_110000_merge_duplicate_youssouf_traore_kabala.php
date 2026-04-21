<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fusionne les doublons « Youssouf TRAORE » (Kabala) : un seul compte conserve les ventes et données liées.
 */
return new class extends Migration
{
    private const AGENCE = 'Kabala';

    public function up(): void
    {
        $kabalaId = DB::table('agences')
            ->whereRaw('LOWER(TRIM(nom)) = ?', [mb_strtolower(trim(self::AGENCE))])
            ->value('id');

        if ($kabalaId === null) {
            return;
        }

        $kabalaId = (int) $kabalaId;

        $users = User::query()
            ->where('agence_id', $kabalaId)
            ->whereRaw('LOWER(TRIM(name)) = ?', ['traore'])
            ->whereRaw('LOWER(TRIM(prenom)) = ?', ['youssouf'])
            ->orderBy('id')
            ->get();

        if ($users->count() < 2) {
            return;
        }

        $canonical = $users->sortByDesc(fn (User $u) => $u->ventes()->count())->first();
        $dups = $users->where('id', '!=', $canonical->id)->values();

        DB::transaction(function () use ($canonical, $dups) {
            foreach ($dups as $dup) {
                $this->mergeUserInto($dup->id, $canonical->id);
            }
        });
    }

    public function down(): void
    {
        // Irréversible.
    }

    private function mergeUserInto(int $fromId, int $toId): void
    {
        if ($fromId === $toId) {
            return;
        }

        DB::table('ventes')->where('user_id', $fromId)->update(['user_id' => $toId]);
        DB::table('clients')->where('user_id', $fromId)->update(['user_id' => $toId]);
        DB::table('reclamations')->where('user_id', $fromId)->update(['user_id' => $toId]);

        if (Schema::hasTable('telephonique_rapports')) {
            DB::table('telephonique_rapports')->where('user_id', $fromId)->update(['user_id' => $toId]);
        }

        if (Schema::hasTable('user_login_logs')) {
            DB::table('user_login_logs')->where('user_id', $fromId)->update(['user_id' => $toId]);
        }

        if (Schema::hasTable('campagne_actions')) {
            DB::table('campagne_actions')->where('user_id', $fromId)->update(['user_id' => $toId]);
        }

        $this->mergePrimes($fromId, $toId);
        $this->mergeContratPrestationReponses($fromId, $toId);
        $this->mergePivotCampagneCommercialContrat($fromId, $toId);

        if (Schema::hasTable('campagne_aide_beneficiaire')) {
            $this->mergePivotSimple('campagne_aide_beneficiaire', $fromId, $toId);
        }

        if (Schema::hasTable('campagne_aide_versements')) {
            $this->mergeCampagneAideVersements($fromId, $toId);
            DB::table('campagne_aide_versements')->where('enregistre_par', $fromId)->update(['enregistre_par' => $toId]);
        }

        User::query()->where('id', $fromId)->delete();
    }

    private function mergePrimes(int $fromId, int $toId): void
    {
        DB::table('primes')->where('user_id', $fromId)->update(['user_id' => $toId]);

        $dupes = DB::table('primes')
            ->select('periode', DB::raw('COUNT(*) as c'))
            ->where('user_id', $toId)
            ->groupBy('periode')
            ->having('c', '>', 1)
            ->pluck('periode');

        foreach ($dupes as $periode) {
            $keep = DB::table('primes')
                ->where('user_id', $toId)
                ->where('periode', $periode)
                ->orderByDesc('id')
                ->first();
            if ($keep) {
                DB::table('primes')
                    ->where('user_id', $toId)
                    ->where('periode', $periode)
                    ->where('id', '!=', $keep->id)
                    ->delete();
            }
        }
    }

    private function mergeContratPrestationReponses(int $fromId, int $toId): void
    {
        if (! Schema::hasTable('contrat_prestation_reponses')) {
            return;
        }

        $rows = DB::table('contrat_prestation_reponses')->where('user_id', $fromId)->get();
        foreach ($rows as $row) {
            $exists = DB::table('contrat_prestation_reponses')
                ->where('campagne_id', $row->campagne_id)
                ->where('user_id', $toId)
                ->exists();
            if ($exists) {
                DB::table('contrat_prestation_reponses')->where('id', $row->id)->delete();
            } else {
                DB::table('contrat_prestation_reponses')->where('id', $row->id)->update(['user_id' => $toId]);
            }
        }
    }

    private function mergePivotCampagneCommercialContrat(int $fromId, int $toId): void
    {
        if (! Schema::hasTable('campagne_commercial_contrat')) {
            return;
        }

        $rows = DB::table('campagne_commercial_contrat')->where('user_id', $fromId)->get();
        foreach ($rows as $row) {
            $exists = DB::table('campagne_commercial_contrat')
                ->where('campagne_id', $row->campagne_id)
                ->where('user_id', $toId)
                ->exists();
            if ($exists) {
                DB::table('campagne_commercial_contrat')->where('id', $row->id)->delete();
            } else {
                DB::table('campagne_commercial_contrat')->where('id', $row->id)->update(['user_id' => $toId]);
            }
        }
    }

    private function mergeCampagneAideVersements(int $fromId, int $toId): void
    {
        $rows = DB::table('campagne_aide_versements')->where('user_id', $fromId)->get();
        foreach ($rows as $row) {
            $other = DB::table('campagne_aide_versements')
                ->where('campagne_id', $row->campagne_id)
                ->where('user_id', $toId)
                ->where('semaine_debut', $row->semaine_debut)
                ->first();
            if ($other) {
                DB::table('campagne_aide_versements')->where('id', $other->id)->update([
                    'montant_carburant' => (int) $other->montant_carburant + (int) $row->montant_carburant,
                    'montant_credit_tel' => (int) $other->montant_credit_tel + (int) $row->montant_credit_tel,
                ]);
                DB::table('campagne_aide_versements')->where('id', $row->id)->delete();
            } else {
                DB::table('campagne_aide_versements')->where('id', $row->id)->update(['user_id' => $toId]);
            }
        }
    }

    private function mergePivotSimple(string $table, int $fromId, int $toId): void
    {
        $rows = DB::table($table)->where('user_id', $fromId)->get();
        foreach ($rows as $row) {
            $exists = DB::table($table)
                ->where('campagne_id', $row->campagne_id)
                ->where('user_id', $toId)
                ->exists();
            if ($exists) {
                DB::table($table)->where('id', $row->id)->delete();
            } else {
                DB::table($table)->where('id', $row->id)->update(['user_id' => $toId]);
            }
        }
    }
};
