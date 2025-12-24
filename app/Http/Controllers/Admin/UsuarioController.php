<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Listar todos los usuarios
    public function index()
    {
        $usuarios = Usuario::orderBy('id_usuario', 'desc')->get();
        
        return Inertia::render('Admin/Usuarios/Index', [
            'usuarios' => $usuarios
        ]);
    }

    // Mostrar formulario de creación
    public function create()
    {
        return Inertia::render('Admin/Usuarios/Create');
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'correo' => 'required|email|unique:usuarios,correo|max:255',
            'contrasena' => 'required|min:6|max:255',
            'rol' => 'required|in:admin,usuario',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'Debe ser un correo válido',
            'correo.unique' => 'Este correo ya está registrado',
            'contrasena.required' => 'La contraseña es obligatoria',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            'rol.required' => 'El rol es obligatorio',
            'rol.in' => 'El rol debe ser admin o usuario',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contrasena' => $request->contrasena, // Por ahora sin hashear
            'rol' => $request->rol,
            'estatus' => 1, // Activo por defecto
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        return Inertia::render('Admin/Usuarios/Edit', [
            'usuario' => $usuario
        ]);
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:255',
            'correo' => 'required|email|max:255|unique:usuarios,correo,' . $id . ',id_usuario',
            'contrasena' => 'nullable|min:6|max:255',
            'rol' => 'required|in:admin,usuario',
            'estatus' => 'required|in:0,1',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'rol' => $request->rol,
            'estatus' => $request->estatus,
        ];

        // Solo actualizar contraseña si se proporciona una nueva
        if ($request->filled('contrasena')) {
            $data['contrasena'] = $request->contrasena; // Por ahora sin hashear
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    // Eliminar (desactivar) usuario
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        // No permitir desactivar el propio usuario
        if ($usuario->id_usuario === auth()->user()->id_usuario) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes desactivar tu propio usuario');
        }
        
        // Cambiar estatus a inactivo
        $usuario->update(['estatus' => 0]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario desactivado exitosamente');
    }
}