<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Mantenimiento;
use App\Models\Componente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserMantenimientoController extends Controller
{
    // Mostrar calendario de mantenimientos
    public function calendario(Request $request)
    {
        $mes = $request->input('mes', now()->month);
        $anio = $request->input('anio', now()->year);

        // Obtener mantenimientos del mes
        $mantenimientos = Mantenimiento::with(['componente', 'tecnico'])
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->fecha)->day;
            });
            
        // Calcular sugerencias de mantenimiento preventivo (cada 25 días)
        $ultimosMantenimientos = Mantenimiento::with('componente')
            ->whereNotNull('id_componente')
            ->selectRaw('MAX(fecha) as ultima_fecha, id_componente')
            ->groupBy('id_componente')
            ->get();

        $sugerencias = [];
        foreach ($ultimosMantenimientos as $mantenimiento) {
            if ($mantenimiento->componente) {
                $ultimaFecha = Carbon::parse($mantenimiento->ultima_fecha);
                $proximaFecha = $ultimaFecha->copy()->addDays(25);
                
                if ($proximaFecha->month == $mes && $proximaFecha->year == $anio) {
                    if (!isset($sugerencias[$proximaFecha->day])) {
                        $sugerencias[$proximaFecha->day] = [];
                    }
                    $sugerencias[$proximaFecha->day][] = [
                        'componente' => $mantenimiento->componente->nombre,
                        'ultima_fecha' => $ultimaFecha->format('Y-m-d'),
                    ];
                }
            }
        }

        return Inertia::render('User/Mantenimientos/Calendario', [
            'mes' => (int)$mes,
            'anio' => (int)$anio,
            'mantenimientos' => $mantenimientos,
            'sugerencias' => $sugerencias,
        ]);
    }

    // Crear nuevo mantenimiento
    public function create()
    {
        $componentes = Componente::where('estatus', 1)->get();

        return Inertia::render('User/Mantenimientos/Create', [
            'componentes' => $componentes,
        ]);
    }

    // Guardar mantenimiento
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo_mantenimiento' => 'required|in:componente,banco',
            'id_componente' => 'required_if:tipo_mantenimiento,componente|nullable|exists:componentes,id_componente',
            'descripcion' => 'required|max:255',
            'notas_tecnicas' => 'nullable',
        ], [
            'fecha.required' => 'La fecha es obligatoria',
            'tipo_mantenimiento.required' => 'Debes seleccionar el tipo de mantenimiento',
            'id_componente.required_if' => 'Debes seleccionar un componente',
            'descripcion.required' => 'La descripción es obligatoria',
        ]);

        Mantenimiento::create([
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
            'notas_tecnicas' => $request->notas_tecnicas,
            'estado' => 'pendiente',
            'id_tecnico' => Auth::user()->id_usuario,
            'id_componente' => $request->tipo_mantenimiento === 'componente' ? $request->id_componente : null,
        ]);

        return redirect()->route('user.mantenimientos.calendario')
            ->with('success', 'Mantenimiento registrado exitosamente');
    }
}