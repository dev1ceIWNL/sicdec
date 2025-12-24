<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false; // Tu tabla no tiene created_at/updated_at

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'rol',
        'estatus',
    ];

    protected $hidden = [
        'contrasena',
    ];

    // Laravel espera 'password', pero tu campo se llama 'contrasena'
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // Laravel espera 'email', pero tu campo se llama 'correo'
    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    // Verificar si el usuario está activo
    public function isActive()
    {
        return $this->estatus == 1;
    }

    // Verificar si es admin
    public function isAdmin()
    {
        return $this->rol === 'admin';
    }
    // Relaciones
    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class, 'id_tecnico', 'id_usuario');
    }

    public function validaciones()
    {
        return $this->hasMany(Validacion::class, 'id_admin', 'id_usuario');
    }

    public function historialBusquedas()
    {
        return $this->hasMany(HistorialBusqueda::class, 'id_usuario', 'id_usuario');
    }

    public function logsAcceso()
    {
        return $this->hasMany(LogAcceso::class, 'id_usuario', 'id_usuario');
    }
}