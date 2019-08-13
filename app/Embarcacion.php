<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Embarcacion extends Model
{
    protected $table = 'embarcaciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 
    ];

    
    /**
     * Definicion de relacion con productores.
     */
    public function embarcaciones() 
    {
        return $this->hasMany(DecomisoGuiaInternacion::class);
    }

}