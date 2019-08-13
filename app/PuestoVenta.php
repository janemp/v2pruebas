<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PuestoVenta extends Model
{
    use SoftDeletes;
    protected $table = 'puestos_venta';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'municipio_id', 'mercado', 'zona', 'direccion', 'cambiado'
    ];

    
    /**
     * Definicion de relaciones.
     */
    public function municipio() 
    {
        return $this->belongsTo(Municipio::class);
    }

    public function persona_puesto_venta()
    {
        return $this->hasMany(PersonaPuestoVenta::class);
    }

}