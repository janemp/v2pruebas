<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    use SoftDeletes;
    protected $table = 'paises';
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
     * Definicion de relacion con productores.
     */
    public function productores() 
    {
        return $this->hasMany(Productor::class);
    }

    /**
     * Definicion de relacion con comercializadore.
     */
    public function comercializadores() 
    {
        return $this->hasMany(Comercializador::class);
    }
}