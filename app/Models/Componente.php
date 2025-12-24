<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    protected $table = 'componentes';
    protected $primaryKey = 'id_componente';
    public $timestamps = false;

    protected $fillable = [
        'numero_serie',
        'nombre',
        'descripcion',
        'tipo',
        'marca',
        'estatus',
        'archivo_pdf'
    ];

    // Relación: Un componente tiene muchos mantenimientos
    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class, 'id_componente', 'id_componente');
    }

    // Verificar si está activo
    public function isActive()
    {
        return $this->estatus == 1;
    }
}