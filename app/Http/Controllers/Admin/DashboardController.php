<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Componente;
use App\Models\Mantenimiento;
use App\Models\Usuario;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas para el dashboard
        $stats = [
            'total_componentes' => Componente::count(),
            'componentes_activos' => Componente::where('estatus', 1)->count(),
            'total_mantenimientos' => Mantenimiento::count(),
            'mantenimientos_pendientes' => Mantenimiento::where('estado', 'pendiente')->count(),
            'total_usuarios' => Usuario::count(),
            'usuarios_activos' => Usuario::where('estatus', 1)->count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
        ]);
    }
}