<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutorizacionCambioPuestoVenta extends Model
{
    use SoftDeletes;
    protected $table = 'autorizacion_cambio_puesto_venta';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'persona_puesto_venta_id', 'adjunto', 'motivo_cambio', 'user_id'
    ];

    
    /**
     * Definicion de relaciones.
     */
    public function persona_puesto_venta() 
    {
        return $this->belongsTo(PersonaPuestoVenta::class);
    }

}