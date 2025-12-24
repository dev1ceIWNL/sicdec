<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialBusqueda extends Model
{
    protected $table = 'historial_busqueda';
    protected $primaryKey = 'id_busqueda';
    public $timestamps = false;

    protected $fillable = [
        'criterio',
        'fecha',
        'id_usuario',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Relación: Un historial pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}