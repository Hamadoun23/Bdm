<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\ClientExportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function __construct(
        private ClientExportService $clientExportService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Client::query()->with(['user.agence', 'typeCarte']);

        if ($user->isChefAgence() && $user->agence_id) {
            $agenceId = $user->agence_id;
            $query->where(function ($q) use ($agenceId) {
                $q->whereHas('user', fn ($q) => $q->where('agence_id', $agenceId))
                    ->orWhereHas('ventes', fn ($q) => $q->where('agence_id', $agenceId));
            });
        }

        $clients = $query->latest()->paginate(20);

        return view('clients.index', compact('clients'));
    }

    public function show(Request $request, Client $client): View
    {
        $this->authorizeClientAccess($request, $client);

        $client->load(['user.agence', 'typeCarte', 'ventes.agence', 'ventes.typeCarte', 'ventes.user']);

        return view('clients.show', compact('client'));
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
        if ($user->isAdmin()) {
            return;
        }
        if ($user->isChefAgence() && $user->agence_id) {
            $agenceId = $user->agence_id;
            $ok = $client->user()->where('agence_id', $agenceId)->exists()
                || $client->ventes()->where('agence_id', $agenceId)->exists();
            if ($ok) {
                return;
            }
        }
        abort(403, 'Accès non autorisé.');
    }
}
