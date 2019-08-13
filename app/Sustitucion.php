<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sustitucion extends Model
{
    protected $table = 'sustituciones';
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
    public function personas() 
    {
        return $this->hasMany(Personas::class);
    }

}