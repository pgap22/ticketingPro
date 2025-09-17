<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index(): void
    {
        $uid  = (int) Session::get('user_id');
        $user = User::with(['role','departments'])->find($uid);
        $role = $user?->role?->name ?? 'Cliente';

        // ===== métricas filtradas por rol =====
        if ($role === 'Administrador') {
            $metrics = [
                'total'      => Ticket::count(),
                'abiertos'   => Ticket::where('status','abierto')->count(),
                'en_proceso' => Ticket::where('status','en_proceso')->count(),
                'cerrados'   => Ticket::where('status','cerrado')->count(),
            ];
        } elseif ($role === 'Agente') {
            $deptIds = $user?->departments?->pluck('id')->all() ?? [];
            // si el agente no tiene deptos, que devuelva 0 en todo
            if (empty($deptIds)) {
                $metrics = ['total'=>0,'abiertos'=>0,'en_proceso'=>0,'cerrados'=>0];
            } else {
                $metrics = [
                    'total'      => Ticket::whereIn('department_id', $deptIds)->count(),
                    'abiertos'   => Ticket::whereIn('department_id', $deptIds)->where('status','abierto')->count(),
                    'en_proceso' => Ticket::whereIn('department_id', $deptIds)->where('status','en_proceso')->count(),
                    'cerrados'   => Ticket::whereIn('department_id', $deptIds)->where('status','cerrado')->count(),
                ];
            }
        } else { // Cliente
            $metrics = [
                'total'      => Ticket::where('user_id', $uid)->count(),
                'abiertos'   => Ticket::where('user_id', $uid)->where('status','abierto')->count(),
                'en_proceso' => Ticket::where('user_id', $uid)->where('status','en_proceso')->count(),
                'cerrados'   => Ticket::where('user_id', $uid)->where('status','cerrado')->count(),
            ];
        }

        // ===== últimos tickets (también filtrados por rol) =====
        if ($role === 'Cliente') {
            $tickets = Ticket::with(['user','agent','department','category'])
                ->where('user_id', $uid)->latest()->limit(10)->get();
        } elseif ($role === 'Agente') {
            $deptIds = $user?->departments?->pluck('id')->all() ?? [];
            $tickets = Ticket::with(['user','agent','department','category'])
                ->when(!empty($deptIds), fn($q) => $q->whereIn('department_id', $deptIds))
                ->whereRaw(empty($deptIds) ? '0=1' : '1=1') // si no tiene deptos → vacío
                ->latest()->limit(10)->get();
        } else { // Admin
            $tickets = Ticket::with(['user','agent','department','category'])
                ->latest()->limit(10)->get();
        }

        $this->render('pages/dashboard/index', [
            'user'    => $user,
            'role'    => $role,
            'metrics' => $metrics,
            'tickets' => $tickets,
        ]);
    }
}
