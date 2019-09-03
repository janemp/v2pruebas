<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoSancion extends Model
{
    protected $table = 'tipos_sancion';
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
    public function sanciones() 
    {
        return $this->hasMany(Sanciones::class);
    }    
}