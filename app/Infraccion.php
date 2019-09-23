<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infraccion extends Model
{
    use SoftDeletes;
    protected $table = 'infracciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'severidad', 'bloqueante','estado'
    ];

    /**
     * Definicion de relaciones.
     */

    // public function sancion() 
    // {
    //     return $this->belongsTo(Sancion::class);
    // }

    public function personas(){
        //return $this->belongsToMany(Persona::class, 'comercializador_infraccions');
        return $this->belongsTo(Persona::class, 'comercializador_infraccions');

    }

    public function comercializadorinfraccion(){
        return $this->belongsToMany(ComercializadorInfraccion::class, 'persona');
    }


}
