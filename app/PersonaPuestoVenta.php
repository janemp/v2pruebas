<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonaPuestoVenta extends Model
{
    use SoftDeletes;
    protected $table = 'persona_puesto_venta';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'persona_id', 'puesto_venta_id',
    ];

    
    /**
     * Definicion de relaciones.
     */
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }

    public function puesto_venta()
    {
        return $this->belongsTo(PuestoVenta::class);
    }

    public function autorizacion_cambio_puesto_venta()
    {
        return $this->hasMany(AutorizacionCambioPuestoVenta::class);
    }

}