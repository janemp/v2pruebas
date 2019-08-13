<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cesacion extends Model
{
    protected $table = 'cesaciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'descripcion','tipo_persona_id'
    ];

    
    /**
     * Definicion de relacion con productores.
     */
    public function Personas() 
    {
        return $this->hasMany(Persona::class);
    }

}