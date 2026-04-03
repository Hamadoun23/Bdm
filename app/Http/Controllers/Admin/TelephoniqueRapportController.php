<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelephoniqueRapport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TelephoniqueRapportController extends Controller
{
    public function index(Request $request): View
    {
        $query = TelephoniqueRapport::query()->with('user.agence')->orderByDesc('date_rapport');

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->user_id);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date_rapport', '>=', $request->date('date_debut'));
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_rapport', '<=', $request->date('date_fin'));
        }

        $rapports = $query->paginate(30)->withQueryString();
        $telephoniques = User::query()
            ->where('role', 'commercial_telephonique')
            ->orderBy('name')
            ->get();

        return view('admin.telephonique-rapports.index', compact('rapports', 'telephoniques'));
    }
}
