<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class UserLoginLogController extends Controller
{
    public function index(Request $request): Response
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

        return Inertia::render('Admin/LoginLogs/Index', [
            'filters' => $request->only(['user_id', 'date_debut', 'date_fin']),
            'utilisateurs' => $utilisateurs->map(fn (User $u) => [
                'id' => $u->id,
                'label' => ($u->prenom ? trim($u->prenom.' '.$u->name) : $u->name).' — '.$u->role.($u->telephone ? " ({$u->telephone})" : ''),
            ])->values(),
            'logs' => [
                'data' => $logs->getCollection()->map(fn (UserLoginLog $log) => [
                    'id' => $log->id,
                    'date' => $log->logged_in_at->format('d/m/Y H:i:s'),
                    'user_nom' => $log->user?->prenom ? trim($log->user->prenom.' '.$log->user->name) : $log->user?->name,
                    'role' => $log->user?->role,
                    'ip' => $log->ip_address,
                    'user_agent' => Str::limit($log->user_agent, 80),
                    'user_agent_full' => $log->user_agent,
                ])->values(),
                'links' => $logs->linkCollection(),
                'from' => $logs->firstItem(),
                'to' => $logs->lastItem(),
                'total' => $logs->total(),
            ],
        ]);
    }
}
