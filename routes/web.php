<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ComponenteController;
use App\Http\Controllers\Admin\MantenimientoController as AdminMantenimientoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\MantenimientoController as UserMantenimientoController;
use App\Http\Controllers\User\UserComponenteController as UserComponenteController;
use App\Http\Controllers\User\ChatbotController;

// Login 
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Auth
Route::middleware(['auth'])->group(function () {
    
    // Usuarios
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/home', [App\Http\Controllers\User\HomeController::class, 'index'])->name('home');

        // Mantos
        Route::get('/mantenimientos/calendario', [App\Http\Controllers\User\UserMantenimientoController::class, 'calendario'])->name('mantenimientos.calendario');
        Route::get('/mantenimientos/crear', [App\Http\Controllers\User\UserMantenimientoController::class, 'create'])->name('mantenimientos.create');
        Route::post('/mantenimientos', [App\Http\Controllers\User\UserMantenimientoController::class, 'store'])->name('mantenimientos.store');

        // Componentes
        Route::get('/componentes', [\App\Http\Controllers\User\UserComponenteController::class, 'index'])->name('componentes.index');
        Route::get('/componentes/{id}', [\App\Http\Controllers\User\UserComponenteController::class, 'show'])->name('componentes.show');

        // IA
        Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
        Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');

        Route::get('/perfil', function () {
        return Inertia::render('User/Perfil');})->name('user.perfil');
    
        Route::get('/faq', function () {
        return Inertia::render('User/FAQ'); })->name('user.faq');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Gestión de Componentes
        Route::get('componentes', [ComponenteController::class, 'index'])->name('componentes.index');
        Route::get('componentes/crear', [ComponenteController::class, 'create'])->name('componentes.create');
        Route::post('componentes', [ComponenteController::class, 'store'])->name('componentes.store');
        Route::get('componentes/{id}', [ComponenteController::class, 'edit'])->name('componentes.edit');
        Route::put('componentes/{id}', [ComponenteController::class, 'update'])->name('componentes.update');
        Route::delete('componentes/{id}', [ComponenteController::class, 'destroy'])->name('componentes.destroy');
        
        // Validación de Mantenimientos
        Route::get('mantenimientos', [AdminMantenimientoController::class, 'index'])->name('mantenimientos.index');
        Route::post('mantenimientos/{id}/validar', [AdminMantenimientoController::class, 'validar'])->name('mantenimientos.validar');

        // Gestión de Usuarios
        Route::get('usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('usuarios/{id}', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

        Route::get('/perfil', function () {
        return Inertia::render('Admin/Perfil'); })->name('admin.perfil');
    
        Route::get('/faq', function () {
        return Inertia::render('Admin/FAQ'); })->name('admin.faq');
    });
});

// testttttttttttttt, edit: si funciona
Route::get('/test', function () {
    return Inertia::render('Test');
});