<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Vente;
use App\Services\ClientExportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function __construct(
        private ClientExportService $clientExportService
    ) {}

    public function index(Request $request): InertiaResponse
    {
        $clients = Client::query()
            ->with(['user.agence', 'typeCarte'])
            ->latest()
            ->paginate(20);

        return Inertia::render('Clients/Index', [
            'clients' => [
                'data' => $clients->getCollection()->map(fn (Client $c) => [
                    'id' => $c->id,
                    'nom_complet' => trim($c->prenom.' '.$c->nom),
                    'telephone' => $c->telephone,
                    'ville' => $c->ville,
                    'type_carte' => $c->typeCarte?->code ?? '?',
                    'commercial' => $c->user->name ?? '—',
                    'statut_carte' => $c->statut_carte,
                ])->values(),
                'links' => $clients->linkCollection(),
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem(),
                'total' => $clients->total(),
            ],
        ]);
    }

    public function show(Request $request, Client $client): InertiaResponse
    {
        $this->authorizeClientAccess($request, $client);

        $client->load(['user.agence', 'typeCarte', 'ventes.agence', 'ventes.typeCarte', 'ventes.user']);

        return Inertia::render('Clients/Show', [
            'client' => [
                'id' => $client->id,
                'nom_complet' => trim($client->prenom.' '.$client->nom),
                'telephone' => $client->telephone,
                'ville' => $client->ville,
                'quartier' => $client->quartier,
                'type_carte' => $client->typeCarte?->code ?? '?',
                'statut_carte' => $client->statut_carte,
                'commercial' => $client->user->name ?? '—',
                'agence' => $client->user?->agence?->nom,
                'created_at' => $client->created_at->format('d/m/Y H:i'),
                'carte_identite_url' => $client->carte_identite ? asset('storage/'.$client->carte_identite) : null,
                'ventes' => $client->ventes->map(fn (Vente $v) => [
                    'id' => $v->id,
                    'date' => $v->created_at->format('d/m/Y H:i'),
                    'type_carte' => $v->typeCarte?->code ?? '?',
                    'commercial' => $v->user->name ?? '—',
                    'agence' => $v->agence->nom ?? '—',
                    'statut_activation' => $v->statut_activation,
                ])->values(),
            ],
        ]);
    }

    public function export(Request $request, Client $client): Response
    {
        $this->authorizeClientAccess($request, $client);

        $format = $request->query('format', 'pdf');
        if (! in_array($format, ['pdf', 'excel', 'word'], true)) {
            abort(422, 'Format d’export invalide.');
        }

        return match ($format) {
            'pdf' => $this->clientExportService->downloadPdf($client),
            'excel' => $this->clientExportService->downloadExcel($client),
            'word' => $this->clientExportService->downloadWord($client),
        };
    }

    private function authorizeClientAccess(Request $request, Client $client): void
    {
        $user = $request->user();
        if ($user && ($user->isAdmin() || $user->isDirection())) {
            return;
        }
        abort(403, 'Accès non autorisé.');
    }
}
