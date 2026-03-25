<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('agence')->whereIn('role', ['commercial', 'chef_agence']);
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        $users = $query->orderBy('role')->orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $agences = Agence::all();
        return view('admin.users.create', compact('agences'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:100',
            'email' => 'required_without:telephone|nullable|email|unique:users,email',
            'telephone' => 'required_without:email|nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:commercial,chef_agence',
            'agence_id' => 'required_if:role,commercial|required_if:role,chef_agence|nullable|exists:agences,id',
            'actif' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email ?: null,
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone ?: null,
            'role' => $request->role,
            'agence_id' => $request->agence_id,
            'actif' => $request->boolean('actif'),
        ]);

        if ($request->role === 'chef_agence' && $request->agence_id) {
            $agence = Agence::find($request->agence_id);
            if ($agence?->chef_id) {
                User::where('id', $agence->chef_id)->update(['agence_id' => null]);
            }
            $agence?->update(['chef_id' => $user->id]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé.');
    }

    public function edit(User $user): View
    {
        $agences = Agence::all();
        return view('admin.users.edit', compact('user', 'agences'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:100',
            'email' => 'required_without:telephone|nullable|email|unique:users,email,' . $user->id,
            'telephone' => 'required_without:email|nullable|string|max:20',
            'role' => 'required|in:commercial,chef_agence',
            'agence_id' => 'required_if:role,commercial|required_if:role,chef_agence|nullable|exists:agences,id',
            'actif' => 'boolean',
        ]);

        if ($user->role === 'chef_agence' && $user->agence_id) {
            Agence::where('chef_id', $user->id)->update(['chef_id' => null]);
        }

        $user->update([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email ?: null,
            'telephone' => $request->telephone ?: null,
            'role' => $request->role,
            'agence_id' => $request->agence_id,
            'actif' => $request->boolean('actif'),
        ]);
        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->role === 'chef_agence' && $request->agence_id) {
            $agence = Agence::find($request->agence_id);
            if ($agence && $agence->chef_id && $agence->chef_id != $user->id) {
                User::where('id', $agence->chef_id)->update(['agence_id' => null]);
            }
            $agence?->update(['chef_id' => $user->id]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'chef_agence' && $user->agence_id) {
            Agence::where('chef_id', $user->id)->update(['chef_id' => null]);
        }
        $user->update(['agence_id' => null]);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
