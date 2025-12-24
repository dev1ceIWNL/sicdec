<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $table = 'mantenimientos';
    protected $primaryKey = 'id_mantenimiento';
    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'descripcion',
        'estado',
        'id_tecnico',
        'id_componente',
        'notas_tecnicas',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación: Un mantenimiento pertenece a un componente
    public function componente()
    {
        return $this->belongsTo(Componente::class, 'id_componente', 'id_componente');
    }

    // Relación: Un mantenimiento pertenece a un técnico (usuario)
    public function tecnico()
    {
        return $this->belongsTo(Usuario::class, 'id_tecnico', 'id_usuario');
    }

    // Relación: Un mantenimiento puede tener una validación
    public function validacion()
    {
        return $this->hasOne(Validacion::class, 'id_mantenimiento', 'id_mantenimiento');
    }
    // Verificar si es mantenimiento a componente o a banco
    public function esMantenimientoComponente()
    {
        return $this->id_componente !== null;
    }

    // Obtener título descriptivo
    public function getTituloAttribute()
    {
        if ($this->esMantenimientoComponente() && $this->componente) {
        return $this->componente->nombre . ' (' . $this->componente->numero_serie . ')';
        }
        return 'Mantenimiento de Banco de Pruebas';
    }
}