<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLoginLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserLoginLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = UserLoginLog::query()->with('user')->orderByDesc('logged_in_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->user_id);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('logged_in_at', '>=', $request->date('date_debut'));
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('logged_in_at', '<=', $request->date('date_fin'));
        }

        $logs = $query->paginate(40)->withQueryString();
        $utilisateurs = User::query()->orderBy('name')->orderBy('prenom')->get();

        return view('admin.login-logs.index', compact('logs', 'utilisateurs'));
    }
}
