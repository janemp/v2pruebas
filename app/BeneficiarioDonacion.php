<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeneficiarioDonacion extends Model
{
    use SoftDeletes;
    protected $table = 'beneficiarios_donacion';
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