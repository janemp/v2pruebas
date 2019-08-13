<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPersona extends Model
{
    protected $table = 'tipos_persona';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];
    
    /**
     * Definicion de relacion de extension.
     */
    public function personas() 
    {
        return $this->hasMany(Personas::class);
    }    
}