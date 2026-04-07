<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class TelephoniqueRapport extends Model
{
    /** Délai après création pendant lequel la fiche peut être modifiée ou supprimée. */
    public const DELAI_MODIFICATION_HEURES = 48;

    protected $table = 'telephonique_rapports';

    protected $fillable = [
        'user_id', 'campagne_id', 'date_rapport',
        'appels_emis', 'appels_joignables', 'appels_non_joignables', 'taux_joignabilite',
        'clients_interesses_nombre', 'clients_interesses_pct',
        'clients_deja_servis_nombre', 'clients_deja_servis_pct',
        'propose_visa', 'propose_gim', 'propose_cauris', 'propose_prepayee',
        'cartes_proposees',
        'nj_repondeur', 'nj_numero_errone', 'nj_hors_reseau', 'nj_autres_nombre', 'nj_autres_precision',
    ];

    protected function casts(): array
    {
        return [
            'date_rapport' => 'date',
            'taux_joignabilite' => 'decimal:2',
            'clients_interesses_pct' => 'decimal:2',
            'clients_deja_servis_pct' => 'decimal:2',
            'cartes_proposees' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campagne(): BelongsTo
    {
        return $this->belongsTo(Campagne::class);
    }

    public function peutEtreModifieOuSupprime(): bool
    {
        if (! $this->created_at instanceof Carbon) {
            return false;
        }

        return $this->created_at->greaterThan(now()->subHours(self::DELAI_MODIFICATION_HEURES));
    }

    public function nombreProposePourType(int $typeCarteId): int
    {
        $arr = $this->cartes_proposees;
        if (! is_array($arr) || $arr === []) {
            return 0;
        }

        return (int) ($arr[(string) $typeCarteId] ?? $arr[$typeCarteId] ?? 0);
    }

    /** Résumé lisible pour les listes admin (ex. "VISA: 2, GIM: 1"). */
    /** Somme des motifs d’analyse des non-joignables (section 5 du formulaire). */
    public function sommeNjMotifs(): int
    {
        return (int) $this->nj_repondeur
            + (int) $this->nj_numero_errone
            + (int) $this->nj_hors_reseau
            + (int) $this->nj_autres_nombre;
    }

    public function njAnalyseCoherente(): bool
    {
        return $this->sommeNjMotifs() <= (int) $this->appels_non_joignables;
    }

    /** % dérivé appels émis (affichage admin si colonnes % null en base). */
    public function pctInteressesCalcule(): ?float
    {
        if ($this->appels_emis <= 0) {
            return null;
        }

        return round((int) $this->clients_interesses_nombre / $this->appels_emis * 100, 2);
    }

    public function pctDejaServisCalcule(): ?float
    {
        if ($this->appels_emis <= 0) {
            return null;
        }

        return round((int) $this->clients_deja_servis_nombre / $this->appels_emis * 100, 2);
    }

    public function resumeCartesProposees(): string
    {
        $arr = $this->cartes_proposees;
        if (! is_array($arr) || $arr === []) {
            return '—';
        }
        $ids = array_map('intval', array_keys($arr));
        $types = TypeCarte::query()->whereIn('id', $ids)->get()->keyBy('id');
        $parts = [];
        foreach ($arr as $id => $n) {
            if ((int) $n <= 0) {
                continue;
            }
            $t = $types->get((int) $id);
            $parts[] = ($t?->code ?? '#'.$id).': '.$n;
        }

        return $parts === [] ? '—' : implode(', ', $parts);
    }
}
