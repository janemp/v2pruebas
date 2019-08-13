<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    protected $table = 'entidades';
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
    public function donaciones() 
    {
        return $this->hasMany(Donacion::class);
    }

}