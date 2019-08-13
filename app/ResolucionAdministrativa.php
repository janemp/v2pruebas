<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResolucionAdministrativa extends Model
{
    use SoftDeletes;
    protected $table = 'resoluciones_administrativas';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'persona_id', 'resolucion', 'fecha_resolucion', 'tipo_resolucion_id', 'numero_taques', 'adjunto', 'observaciones', 'activo'
    ];

    
    /**
     * Definicion de relacion .
     */
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }

    public function tipo_resolucion() 
    {
        return $this->belongsTo(TipoResolucion::class);
    }
}