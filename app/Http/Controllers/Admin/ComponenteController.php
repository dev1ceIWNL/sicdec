<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Componente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComponenteController extends Controller
{
    // Listar todos los componentes
    public function index()
    {
        $componentes = Componente::orderBy('id_componente', 'desc')->get();
        
        return Inertia::render('Admin/Componentes/Index', [
            'componentes' => $componentes
        ]);
    }

    // Mostrar formulario de creación
    public function create()
    {
        return Inertia::render('Admin/Componentes/Create');
    }

    // Guardar nuevo componente
    public function store(Request $request)
{
    $request->validate([
        'numero_serie' => 'required|unique:componentes,numero_serie|max:255',
        'nombre' => 'required|max:255',
        'descripcion' => 'nullable|max:255',
        'tipo' => 'required|in:inyector,bomba',
        'marca' => 'nullable|max:255',
        'archivo_pdf' => 'nullable|file|mimes:pdf|max:10240', // ← Agregar validación (max 10MB)
    ], [
        'numero_serie.required' => 'El número de serie es obligatorio',
        'numero_serie.unique' => 'Este número de serie ya existe',
        'nombre.required' => 'El nombre es obligatorio',
        'tipo.required' => 'El tipo es obligatorio',
        'tipo.in' => 'El tipo debe ser inyector o bomba',
        'archivo_pdf.mimes' => 'El archivo debe ser un PDF',
        'archivo_pdf.max' => 'El archivo no debe pesar más de 10MB',
    ]);

    $data = [
        'numero_serie' => $request->numero_serie,
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'tipo' => $request->tipo,
        'marca' => $request->marca,
        'estatus' => 1,
    ];

    // Guardar archivo PDF si existe
    if ($request->hasFile('archivo_pdf')) {
        $file = $request->file('archivo_pdf');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('componentes/pdf', $fileName, 'public');
        $data['archivo_pdf'] = $filePath;
    }

    Componente::create($data);

    return redirect()->route('admin.componentes.index')
        ->with('success', 'Componente creado exitosamente');
}
    // Mostrar formulario de edición
    public function edit($id)
    {
        $componente = Componente::findOrFail($id);
        
        return Inertia::render('Admin/Componentes/Edit', [
            'componente' => $componente
        ]);
    }

    // Actualizar componente
    public function update(Request $request, $id)
{
    $componente = Componente::findOrFail($id);

    $request->validate([
        'numero_serie' => 'required|max:255|unique:componentes,numero_serie,' . $id . ',id_componente',
        'nombre' => 'required|max:255',
        'descripcion' => 'nullable|max:255',
        'tipo' => 'required|in:inyector,bomba',
        'marca' => 'nullable|max:255',
        'estatus' => 'required|in:0,1',
        'archivo_pdf' => 'nullable|file|mimes:pdf|max:10240', // ← Agregar validación
    ]);

    $data = [
        'numero_serie' => $request->numero_serie,
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'tipo' => $request->tipo,
        'marca' => $request->marca,
        'estatus' => $request->estatus,
    ];

    // Actualizar archivo PDF si se sube uno nuevo
    if ($request->hasFile('archivo_pdf')) {
        // Eliminar archivo anterior si existe
        if ($componente->archivo_pdf && \Storage::disk('public')->exists($componente->archivo_pdf)) {
            \Storage::disk('public')->delete($componente->archivo_pdf);
        }

        $file = $request->file('archivo_pdf');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('componentes/pdf', $fileName, 'public');
        $data['archivo_pdf'] = $filePath;
    }

    $componente->update($data);

    return redirect()->route('admin.componentes.index')
        ->with('success', 'Componente actualizado exitosamente');
}
    // Eliminar (desactivar) componente
    public function destroy($id)
    {
        $componente = Componente::findOrFail($id);
        
        // Cambiar estatus a inactivo en lugar de eliminar
        $componente->update(['estatus' => 0]);

        return redirect()->route('admin.componentes.index')
            ->with('success', 'Componente desactivado exitosamente');
    }
}