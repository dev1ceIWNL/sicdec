<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAcceso extends Model
{
    protected $table = 'logs_acceso';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'accion',
        'id_usuario',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Relación: Un log pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}