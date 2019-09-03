<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoInfraccion extends Model
{
    protected $table = 'tipos_infraccion';
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
    public function infracciones() 
    {
        return $this->hasMany(Infracciones::class);
    }    
}