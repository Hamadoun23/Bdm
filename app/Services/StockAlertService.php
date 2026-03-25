<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Collection;

class StockAlertService
{
    public function __construct(
        private int $seuil = 10
    ) {}

    public function getAlertes(): Collection
    {
        return Stock::with(['agence', 'typeCarte'])
            ->where('quantite', '<=', $this->seuil)
            ->where('quantite', '>=', 0)
            ->get();
    }

    public function setSeuil(int $seuil): void
    {
        $this->seuil = $seuil;
    }
}
