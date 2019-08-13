<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';
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
    public function guias_internacion() 
    {
        return $this->hasMany(GuiasInternacion::class);
    }

}