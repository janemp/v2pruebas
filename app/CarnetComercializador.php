<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarnetComercializador extends Model
{
    use SoftDeletes;
    protected $table = 'carnet_comercializador';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'persona_id', 'carnet', 'fecha_emision', 'fecha_conclusion', 'duplicado', 'renovado'
    ];

    
    /**
     * Definicion de relacion .
     */
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }    
}