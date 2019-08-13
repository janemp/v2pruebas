<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarnetProductor extends Model
{
    use SoftDeletes;
    protected $table = 'carnet_productor';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'persona_id', 'departamento_id', 'provincia_id', 'municipio_id', 'federacion_id', 'regional_id',
        'central_id', 'comunidad_id', 'codigo_socio', 'codigo', 'carnet', 'superficie', 'fecha_emision',
        'fecha_conclusion', 'duplicado', 'user_id', 'renovado'
    ];

    
    /**
     * Definicion de relacion .
     */
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }

    public function departamento() 
    {
        return $this->belongsTo(Departamento::class);
    }
    
    public function provincia() 
    {
        return $this->belongsTo(Provincia::class);
    }

    public function municipio() 
    {
        return $this->belongsTo(Municipio::class);
    }

    public function federacion() 
    {
        return $this->belongsTo(Federacion::class);
    }

    public function regional() 
    {
        return $this->belongsTo(Regional::class);
    }

    public function comunidad() 
    {
        return $this->belongsTo(Comunidad::class);
    }

    public function central() 
    {
        return $this->belongsTo(Central::class);
    }

}