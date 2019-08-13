<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntidadInvestigacion extends Model
{
    use SoftDeletes;
    protected $table = 'entidades_investigacion';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'persona_id', 'nombre', 'direccion', 'telefono'
    ];

    
    /**
     * Definicion de relacion .
     */
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }
    
}