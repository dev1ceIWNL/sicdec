<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Usuario;

class AuthController extends Controller
{
    // Mostrar la página de login
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required',
        ]);

        // Buscar usuario por correo
        $usuario = Usuario::where('correo', $request->correo)->first();

        // Verificar si existe y está activo
        if (!$usuario || !$usuario->isActive()) {
            return back()->withErrors([
                'correo' => 'Las credenciales no son correctas o el usuario está inactivo.',
            ]);
        }

        // Por ahora, comparación directa (luego usaremos hash)
        if ($usuario->contrasena !== $request->contrasena) {
            return back()->withErrors([
                'correo' => 'Las credenciales no son correctas.',
            ]);
        }

        // Login exitoso
        Auth::login($usuario);

        // Redirigir según el rol
        // Redirigir según el rol
       if ($usuario->isAdmin()) {
    return redirect()->route('admin.dashboard');
} else {
    return redirect()->route('user.home');
}
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}