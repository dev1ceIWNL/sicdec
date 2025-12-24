<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Componente;
use App\Models\Mantenimiento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserComponenteController extends Controller
{
    // Página de búsqueda
    public function index(Request $request)
    {
        $query = Componente::query();

        // Filtro de búsqueda por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('numero_serie', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por estado
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        $componentes = $query->orderBy('nombre')->get();

        return Inertia::render('User/Componentes/Index', [
            'componentes' => $componentes,
            'filters' => [
                'search' => $request->search,
                'tipo' => $request->tipo,
                'estatus' => $request->estatus,
            ]
        ]);
    }

    // Ver detalles de un componente
    public function show($id)
    {
        $componente = Componente::findOrFail($id);

        // Obtener historial de mantenimientos
        $mantenimientos = Mantenimiento::with(['tecnico', 'validacion.admin'])
            ->where('id_componente', $id)
            ->orderBy('fecha', 'desc')
            ->get();

        // Obtener último mantenimiento
        $ultimoMantenimiento = $mantenimientos->first();

        // Calcular días desde el último mantenimiento
        $diasDesdeUltimo = null;
        if ($ultimoMantenimiento) {
            $diasDesdeUltimo = now()->diffInDays($ultimoMantenimiento->fecha);
        }

        return Inertia::render('User/Componentes/Show', [
            'componente' => $componente,
            'mantenimientos' => $mantenimientos,
            'ultimoMantenimiento' => $ultimoMantenimiento,
            'diasDesdeUltimo' => $diasDesdeUltimo,
        ]);
    }
}