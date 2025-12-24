<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validacion extends Model
{
    protected $table = 'validaciones';
    protected $primaryKey = 'id_validacion';
    public $timestamps = false;

    protected $fillable = [
        'fecha_validacion',
        'decision',
        'comentario',
        'id_mantenimiento',
        'id_admin',
    ];

    protected $casts = [
        'fecha_validacion' => 'date',
    ];

    // Relación: Una validación pertenece a un mantenimiento
    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class, 'id_mantenimiento', 'id_mantenimiento');
    }

    // Relación: Una validación pertenece a un admin (usuario)
    public function admin()
    {
        return $this->belongsTo(Usuario::class, 'id_admin', 'id_usuario');
    }
}