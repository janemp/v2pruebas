<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComercializadorInfraccion extends Model
{
    use SoftDeletes;
    protected $table = 'comercializador_infraccions';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'persona_id', 'sancion_id',
    ];

    
    /**
     * Definicion de relaciones.
     */
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }

    public function sancion()
    {
        return $this->belongsTo(Sancion::class);
    }
}