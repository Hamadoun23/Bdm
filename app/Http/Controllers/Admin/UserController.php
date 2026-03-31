<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('agence')->whereIn('role', ['commercial', 'direction']);

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

        $users = $query->orderBy('role')->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
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
            'role' => 'required|in:commercial,direction',
            'agence_id' => 'nullable|exists:agences,id',
            'actif' => 'boolean',
            'adresse_contrat' => 'nullable|string|max:5000',
            'piece_identite_ref' => 'nullable|string|max:191',
        ]);

        if ($request->role === 'commercial' && ! $request->filled('agence_id')) {
            return back()->withErrors(['agence_id' => 'L’agence est obligatoire pour un commercial.'])->withInput();
        }

        User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->role === 'commercial' ? null : ($request->email ?: null),
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone ?: null,
            'role' => $request->role,
            'agence_id' => $request->role === 'direction' ? null : $request->agence_id,
            'actif' => $request->boolean('actif'),
            'adresse_contrat' => $request->role === 'commercial' ? ($request->adresse_contrat ?: null) : null,
            'piece_identite_ref' => $request->role === 'commercial' ? ($request->piece_identite_ref ?: null) : null,
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
            'role' => 'required|in:commercial,direction',
            'agence_id' => 'nullable|exists:agences,id',
            'actif' => 'boolean',
            'adresse_contrat' => 'nullable|string|max:5000',
            'piece_identite_ref' => 'nullable|string|max:191',
        ]);

        if ($request->role === 'commercial' && ! $request->filled('agence_id')) {
            return back()->withErrors(['agence_id' => 'L’agence est obligatoire pour un commercial.'])->withInput();
        }

        $user->update([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->role === 'commercial' ? null : ($request->email ?: null),
            'telephone' => $request->telephone ?: null,
            'role' => $request->role,
            'agence_id' => $request->role === 'direction' ? null : $request->agence_id,
            'actif' => $request->boolean('actif'),
            'adresse_contrat' => $request->role === 'commercial' ? ($request->adresse_contrat ?: null) : null,
            'piece_identite_ref' => $request->role === 'commercial' ? ($request->piece_identite_ref ?: null) : null,
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
}
