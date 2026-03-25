<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class RapportController extends Controller
{
    public function index(): View
    {
        return view('admin.rapports.index');
    }

    public function export(Request $request)
    {
        $type = $request->query('type', 'mensuel'); // hebdomadaire, mensuel
        $agenceId = $request->query('agence');
        $date = $request->query('date', now()->format('Y-m'));

        if ($type === 'hebdomadaire') {
            $dateDebut = Carbon::parse($date . '-01')->startOfWeek();
            $dateFin = $dateDebut->copy()->endOfWeek();
        } else {
            $dateDebut = Carbon::parse($date . '-01')->startOfMonth();
            $dateFin = $dateDebut->copy()->endOfMonth();
        }

        $ventes = Vente::with(['client', 'user', 'agence', 'typeCarte'])
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->when($agenceId, fn($q) => $q->where('agence_id', $agenceId))
            ->orderBy('created_at')
            ->get();

        $filename = "rapport_ventes_{$type}_{$dateDebut->format('Y-m-d')}.csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($ventes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Client', 'Téléphone', 'Type carte', 'Montant', 'Commercial', 'Agence', 'Statut'], ';');
            foreach ($ventes as $v) {
                fputcsv($file, [
                    $v->created_at->format('d/m/Y H:i'),
                    $v->client->prenom . ' ' . $v->client->nom,
                    $v->client->telephone ?? '',
                    $v->typeCarte?->code ?? '-',
                    $v->montant ?? '',
                    $v->user->name ?? '',
                    $v->agence->nom ?? '',
                    $v->statut_activation,
                ], ';');
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
