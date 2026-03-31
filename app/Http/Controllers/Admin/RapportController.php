<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campagne;
use App\Models\Client;
use App\Models\User;
use App\Models\Vente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RapportController extends Controller
{
    public function index(Request $request): View
    {
        Campagne::syncStatuts();
        /** @var User $user */
        $user = $request->user();

        $campagnes = Campagne::query()->orderByDesc('date_debut')->orderByDesc('id')->get();

        foreach ($campagnes as $campagne) {
            $campagne->setAttribute('nb_ventes_rapport', $campagne->ventes()->count());
        }

        return view('rapports.index', compact('campagnes', 'user'));
    }

    public function campagneVentes(Request $request, Campagne $campagne): View
    {
        $this->assertUserCanAccessCampagne($request->user(), $campagne);

        $ventes = Vente::with(['client', 'user', 'agence', 'typeCarte', 'campagne'])
            ->where('campagne_id', $campagne->id)
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('rapports.campagne-ventes', compact('campagne', 'ventes'));
    }

    public function campagneClients(Request $request, Campagne $campagne): View
    {
        $this->assertUserCanAccessCampagne($request->user(), $campagne);

        $clientIds = Vente::query()
            ->where('campagne_id', $campagne->id)
            ->distinct()
            ->pluck('client_id')
            ->filter()
            ->values();

        $clients = Client::query()
            ->with(['user.agence', 'typeCarte'])
            ->whereIn('id', $clientIds)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('rapports.campagne-clients', compact('campagne', 'clients'));
    }

    public function export(Request $request): StreamedResponse
    {
        /** @var User $user */
        $user = $request->user();
        if (! $user->isAdmin() && ! $user->isDirection()) {
            abort(403);
        }

        $type = $request->query('type', 'mensuel');
        $agenceId = $request->query('agence');
        $date = $request->query('date', now()->format('Y-m'));

        if ($type === 'hebdomadaire') {
            $dateDebut = Carbon::parse($date.'-01')->startOfWeek();
            $dateFin = $dateDebut->copy()->endOfWeek();
        } else {
            $dateDebut = Carbon::parse($date.'-01')->startOfMonth();
            $dateFin = $dateDebut->copy()->endOfMonth();
        }

        $ventesQuery = Vente::with(['client', 'user', 'agence', 'typeCarte', 'campagne'])
            ->whereBetween('created_at', [$dateDebut, $dateFin]);

        if ($agenceId !== null && $agenceId !== '') {
            $ventesQuery->where('agence_id', $agenceId);
        }

        $ventes = $ventesQuery->orderBy('created_at')->get();

        $filename = "rapport_ventes_{$type}_{$dateDebut->format('Y-m-d')}.csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($ventes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Campagne', 'Client', 'Téléphone', 'Type carte', 'Montant', 'Commercial', 'Agence', 'Statut'], ';');
            foreach ($ventes as $v) {
                fputcsv($file, [
                    $v->created_at->format('d/m/Y H:i'),
                    $v->campagne?->nom ?? '-',
                    $v->client->prenom.' '.$v->client->nom,
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

    private function assertUserCanAccessCampagne(?User $user, Campagne $campagne): void
    {
        if (! $user) {
            abort(403);
        }
        if ($user->isAdmin() || $user->isDirection()) {
            return;
        }
        abort(403, 'Accès non autorisé à cette campagne.');
    }
}
