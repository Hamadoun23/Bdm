<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Campagne;
use App\Models\CommercialAgenceTransfert;
use App\Models\ContratPrestationReponse;
use App\Models\User;
use App\Models\Vente;
use App\Services\TransfertVentesAgenceService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use InvalidArgumentException;

class UserController extends Controller
{
    public function __construct(
        private TransfertVentesAgenceService $transfertVentesAgenceService
    ) {}

    public function index(Request $request): View
    {
        $query = User::with('agence')->whereIn('role', ['commercial', 'commercial_telephonique', 'direction']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->q);
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(function ($sub) use ($like) {
                $sub->where('name', 'like', $like)
                    ->orWhere('prenom', 'like', $like)
                    ->orWhere('telephone', 'like', $like);
            });
        }

        if ($request->filled('contrat') && in_array($request->contrat, ['accepte', 'rejete', 'en_attente', 'non_signataire'], true)) {
            $matchingIds = $this->userIdsMatchingContratFiltre($request->contrat, $request);
            $query->whereIn('id', $matchingIds ?: [0]);
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(15)->withQueryString();

        $contratParUser = [];
        foreach ($users as $u) {
            $contratParUser[$u->id] = $this->contratStatutPourCampagneActive($u);
        }

        return view('admin.users.index', compact('users', 'contratParUser'));
    }

    /**
     * @return list<int>
     */
    private function userIdsMatchingContratFiltre(string $filtre, Request $request): array
    {
        $q = User::query()->whereIn('role', ['commercial', 'commercial_telephonique'])->whereNotNull('agence_id');

        if ($request->filled('role') && in_array($request->role, ['commercial', 'commercial_telephonique'], true)) {
            $q->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->q);
            $like = '%'.addcslashes($term, '%_\\').'%';
            $q->where(function ($sub) use ($like) {
                $sub->where('name', 'like', $like)
                    ->orWhere('prenom', 'like', $like)
                    ->orWhere('telephone', 'like', $like);
            });
        }

        $ids = [];
        $q->chunkById(100, function ($chunk) use ($filtre, &$ids) {
            foreach ($chunk as $u) {
                if ($this->contratStatutPourCampagneActive($u) === $filtre) {
                    $ids[] = $u->id;
                }
            }
        });

        return $ids;
    }

    private function contratStatutPourCampagneActive(User $u): ?string
    {
        if (! $u->isCommercialOuTelephonique() || ! $u->agence_id) {
            return null;
        }
        Campagne::syncStatuts();
        $c = Campagne::getActiveForAgence($u->agence_id);
        if (! $c || ! $c->userEstSignataireContrat($u)) {
            return 'non_signataire';
        }
        $r = ContratPrestationReponse::where('campagne_id', $c->id)->where('user_id', $u->id)->first();

        return $r?->statut ?? 'en_attente';
    }

    public function create(): View
    {
        $agences = Agence::orderBy('nom')->get();

        return view('admin.users.create', compact('agences'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email',
            'telephone' => ['required', 'string', 'max:20', Rule::unique('users', 'telephone')],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:commercial,commercial_telephonique,direction',
            'agence_id' => 'nullable|exists:agences,id',
            'actif' => 'boolean',
            'adresse_contrat' => 'nullable|string|max:5000',
            'piece_identite_ref' => 'nullable|string|max:191',
        ]);

        if (in_array($request->role, ['commercial', 'commercial_telephonique'], true) && ! $request->filled('agence_id')) {
            return back()->withErrors(['agence_id' => 'L’agence est obligatoire pour ce type de profil.'])->withInput();
        }

        $terrainOuTel = in_array($request->role, ['commercial', 'commercial_telephonique'], true);

        User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $terrainOuTel ? null : ($request->email ?: null),
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone ?: null,
            'role' => $request->role,
            'agence_id' => $request->role === 'direction' ? null : $request->agence_id,
            'actif' => $request->boolean('actif'),
            'adresse_contrat' => $terrainOuTel ? ($request->adresse_contrat ?: null) : null,
            'piece_identite_ref' => $terrainOuTel ? ($request->piece_identite_ref ?: null) : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé.');
    }

    public function edit(User $user): View
    {
        $agences = Agence::orderBy('nom')->get();

        return view('admin.users.edit', compact('user', 'agences'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'telephone' => ['required', 'string', 'max:20', Rule::unique('users', 'telephone')->ignore($user->id)],
            'role' => 'required|in:commercial,commercial_telephonique,direction',
            'agence_id' => 'nullable|exists:agences,id',
            'actif' => 'boolean',
            'adresse_contrat' => 'nullable|string|max:5000',
            'piece_identite_ref' => 'nullable|string|max:191',
        ]);

        if (in_array($request->role, ['commercial', 'commercial_telephonique'], true) && ! $request->filled('agence_id')) {
            return back()->withErrors(['agence_id' => 'L’agence est obligatoire pour ce type de profil.'])->withInput();
        }

        $terrainOuTel = in_array($request->role, ['commercial', 'commercial_telephonique'], true);

        $user->update([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $terrainOuTel ? null : ($request->email ?: null),
            'telephone' => $request->telephone ?: null,
            'role' => $request->role,
            'agence_id' => $request->role === 'direction' ? null : $request->agence_id,
            'actif' => $request->boolean('actif'),
            'adresse_contrat' => $terrainOuTel ? ($request->adresse_contrat ?: null) : null,
            'piece_identite_ref' => $terrainOuTel ? ($request->piece_identite_ref ?: null) : null,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->update(['agence_id' => null]);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }

    public function transfertAgenceForm(Request $request, User $user): View
    {
        if (! $user->isCommercial() && ! $user->isCommercialTelephonique()) {
            abort(404);
        }

        $query = Vente::query()
            ->where('user_id', $user->id)
            ->with(['campagne', 'agence', 'typeCarte', 'client']);

        if ($request->filled('du')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->du)->startOfDay());
        }
        if ($request->filled('au')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->au)->endOfDay());
        }
        if ($request->filled('campagne_id')) {
            $query->where('campagne_id', (int) $request->campagne_id);
        }
        if ($request->filled('agence_id')) {
            $query->where('agence_id', (int) $request->agence_id);
        }

        $ventes = $query->orderByDesc('created_at')->paginate(25)->withQueryString();

        $campagnes = Campagne::query()->orderByDesc('date_debut')->orderByDesc('id')->get();
        $agences = Agence::query()->orderBy('ordre')->orderBy('nom')->get();

        return view('admin.users.transfert-agence', compact('user', 'ventes', 'campagnes', 'agences'));
    }

    public function transfertAgenceApply(Request $request, User $user): RedirectResponse
    {
        if (! $user->isCommercial() && ! $user->isCommercialTelephonique()) {
            abort(404);
        }

        $request->validate([
            'agence_cible_id' => 'required|exists:agences,id',
            'vente_ids' => 'nullable|array',
            'vente_ids.*' => ['integer', Rule::exists('ventes', 'id')->where('user_id', $user->id)],
            'maj_profil' => 'boolean',
            'note' => 'nullable|string|max:2000',
        ]);

        $agenceCibleId = (int) $request->agence_cible_id;
        $venteIds = array_values(array_filter(array_map('intval', $request->input('vente_ids', []))));
        $majProfil = $request->boolean('maj_profil');

        if ($venteIds === [] && ! $majProfil) {
            return back()->withErrors(['vente_ids' => 'Cochez au moins une vente et/ou activez « Mettre à jour l’agence du profil ».'])->withInput();
        }

        $admin = $request->user();
        if (! $admin || ! $admin->isAdmin()) {
            abort(403);
        }

        $profilAvant = $user->agence_id ? (int) $user->agence_id : null;
        $snapshotsResult = ['count' => 0, 'snapshots' => []];

        try {
            if ($venteIds !== []) {
                $snapshotsResult = $this->transfertVentesAgenceService->reattribuerVentes(
                    $user,
                    $venteIds,
                    $agenceCibleId,
                    $admin
                );
                if ($snapshotsResult['count'] === 0 && ! $majProfil) {
                    return back()->withErrors([
                        'transfert' => 'Aucune vente n’a été modifiée (déjà rattachées à l’agence cible). Cochez « Mettre à jour l’agence du profil » si vous souhaitez seulement changer le profil.',
                    ])->withInput();
                }
            }

            if ($majProfil) {
                $user->update(['agence_id' => $agenceCibleId]);
            }

            if ($snapshotsResult['count'] > 0 || $majProfil) {
                CommercialAgenceTransfert::query()->create([
                    'commercial_user_id' => $user->id,
                    'admin_user_id' => $admin->id,
                    'nouvelle_agence_id' => $agenceCibleId,
                    'snapshots' => $snapshotsResult['snapshots'],
                    'profil_agence_avant' => $majProfil ? $profilAvant : null,
                    'profil_agence_apres' => $majProfil ? $agenceCibleId : null,
                    'note' => $request->note,
                ]);
            }
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['transfert' => $e->getMessage()])->withInput();
        }

        $msg = [];
        if ($snapshotsResult['count'] > 0) {
            $msg[] = $snapshotsResult['count'].' vente(s) réattribuée(s).';
        }
        if ($majProfil) {
            $msg[] = 'Agence du profil mise à jour.';
        }

        return redirect()
            ->route('admin.users.transfert-agence', array_filter([
                'user' => $user->id,
                'du' => $request->du,
                'au' => $request->au,
                'campagne_id' => $request->campagne_id,
                'agence_id' => $request->agence_id,
            ], fn ($v) => $v !== null && $v !== ''))
            ->with('success', implode(' ', $msg));
    }
}
