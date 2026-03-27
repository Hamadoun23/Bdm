<?php

namespace App\Services;

use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ClientExportService
{
    public function downloadPdf(Client $client): SymfonyResponse
    {
        $client->loadMissing(['user.agence', 'typeCarte', 'ventes.agence', 'ventes.typeCarte', 'ventes.user']);

        $filename = 'client_'.$client->id.'_'.now()->format('Y-m-d').'.pdf';
        $identite = $this->identiteForExports($client);

        return Pdf::loadView('exports.client-pdf', [
            'client' => $client,
            'identite' => $identite,
        ])->download($filename);
    }

    /**
     * Fichier CSV (UTF-8 BOM) ouvrable dans Excel.
     */
    public function downloadExcel(Client $client): SymfonyResponse
    {
        $client->loadMissing(['user.agence', 'typeCarte', 'ventes.agence', 'ventes.typeCarte', 'ventes.user']);

        $filename = 'client_'.$client->id.'_'.now()->format('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $rows = $this->clientKeyValueRows($client);

        $callback = static function () use ($rows): void {
            echo "\xEF\xBB\xBF";
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Champ', 'Valeur'], ';');
            foreach ($rows as [$k, $v]) {
                fputcsv($out, [$k, $v], ';');
            }
            fclose($out);
        };

        return ResponseFacade::stream($callback, 200, $headers);
    }

    /**
     * Document Word sans PhpWord (pas d’extension zip requise) : HTML ouvert par Microsoft Word.
     */
    public function downloadWord(Client $client): SymfonyResponse
    {
        $client->loadMissing(['user.agence', 'typeCarte', 'ventes.agence', 'ventes.typeCarte', 'ventes.user']);
        $identite = $this->identiteForExports($client);

        $filename = 'client_'.$client->id.'_'.now()->format('Y-m-d').'.doc';

        $headers = [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $html = view('exports.client-word', [
            'client' => $client,
            'identite' => $identite,
        ])->render();

        return response($html, 200, $headers);
    }

    /**
     * @return array{
     *   has_file: bool,stored: bool,
     *   image_src: ?string,
     *   is_pdf: bool,
     *   download_url: ?string,
     *   label: ?string
     * }
     */
    private function identiteForExports(Client $client): array
    {
        $base = [
            'has_file' => false,
            'stored' => false,
            'image_src' => null,
            'is_pdf' => false,
            'download_url' => null,
            'label' => null,
        ];

        if (! $client->carte_identite) {
            return $base;
        }

        $disk = Storage::disk('public');
        $downloadUrl = asset('storage/'.$client->carte_identite);
        $base['has_file'] = true;
        $base['label'] = basename($client->carte_identite);
        $base['download_url'] = $downloadUrl;

        if (! $disk->exists($client->carte_identite)) {
            return $base;
        }

        $base['stored'] = true;
        $abs = $disk->path($client->carte_identite);
        $mime = @mime_content_type($abs) ?: '';

        if (str_starts_with($mime, 'image/')) {
            $raw = @file_get_contents($abs);
            if ($raw !== false) {
                $base['image_src'] = 'data:'.$mime.';base64,'.base64_encode($raw);
            }

            return $base;
        }

        if ($mime === 'application/pdf' || str_ends_with(strtolower($client->carte_identite), '.pdf')) {
            $base['is_pdf'] = true;
        }

        return $base;
    }

    /** @return list<array{0: string, 1: string}> */
    private function clientKeyValueRows(Client $client): array
    {
        $rows = [
            ['Identifiant', (string) $client->id],
            ['Prénom', $client->prenom],
            ['Nom', $client->nom],
            ['Téléphone', $client->telephone ?? '—'],
            ['Ville', $client->ville ?? '—'],
            ['Quartier', $client->quartier ?? '—'],
            ['Type de carte', $client->typeCarte?->code ?? '—'],
            ['Statut carte', $client->statut_carte],
            ['Commercial', $client->user?->name ?? '—'],
            ['Agence (commercial)', $client->user?->agence?->nom ?? '—'],
            ['Créé le', $client->created_at->format('d/m/Y H:i')],
        ];

        if ($client->carte_identite) {
            $rows[] = ['Pièce d’identité (fichier)', $client->carte_identite];
        }

        return $rows;
    }
}
