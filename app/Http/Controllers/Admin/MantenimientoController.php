<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mantenimiento;
use App\Models\Validacion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class MantenimientoController extends Controller
{
    // Listar mantenimientos pendientes y recientes
    public function index()
    {
        $mantenimientos = Mantenimiento::with(['componente', 'tecnico', 'validacion.admin'])
            ->orderBy('id_mantenimiento', 'desc')
            ->get();
        
        return Inertia::render('Admin/Mantenimientos/Index', [
            'mantenimientos' => $mantenimientos
        ]);
    }

    // Validar mantenimiento (aprobar o rechazar)
    public function validar(Request $request, $id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);

        $request->validate([
            'decision' => 'required|in:aprobado,rechazado',
            'comentario' => 'nullable|max:255',
        ], [
            'decision.required' => 'La decisión es obligatoria',
            'decision.in' => 'La decisión debe ser aprobado o rechazado',
        ]);

        // Actualizar estado del mantenimiento
        $mantenimiento->update([
            'estado' => $request->decision === 'aprobado' ? 'aprobado' : 'rechazado'
        ]);

        // Crear registro de validación
        Validacion::create([
            'fecha_validacion' => now()->toDateString(),
            'decision' => $request->decision,
            'comentario' => $request->comentario,
            'id_mantenimiento' => $mantenimiento->id_mantenimiento,
            'id_admin' => Auth::user()->id_usuario,
        ]);

        return redirect()->route('admin.mantenimientos.index')
            ->with('success', 'Mantenimiento ' . $request->decision . ' exitosamente');
    }
}