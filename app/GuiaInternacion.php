<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuiaInternacion extends Model
{
    use SoftDeletes;
    protected $table = 'guias_internacion';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'persona_id', 'ruta_id', 'user_id', 'correlativo', 'orden_comunal', 'fecha_orden_comunal',
        'numero_taques', 'libras', 'procedencia', 'conductor', 'licencia', 'vehiculo_id', 'marca_id', 'color',
        'numero_placa', 'controlado', 'retenido'
    ];

    /**
     * Definicion de relaciones.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
    
    public function ruta() 
    {
        return $this->belongsTo(Ruta::class);
    }

    public function vehiculo() 
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function marca() 
    {
        return $this->belongsTo(Marca::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function control_guia_internacion() 
    {
        return $this->hasMany(ControlGuiaInternacion::class);
    }

    public function retencion() 
    {
        return $this->hasMany(Retencion::class);
    }
}