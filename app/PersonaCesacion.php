<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonaCesacion extends Model
{
    protected $table = 'persona_cesacion';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'persona_id', 'cesacion_id','adjunto_cesacion', 'activo', 'user_id'
    ];

    
    /**
     * Definicion de relacion con productores.
     */
    // public function Personas() 
    // {
    //     return $this->hasMany(Persona::class);
    // }

}