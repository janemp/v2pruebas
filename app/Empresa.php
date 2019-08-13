<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;
    protected $table = 'empresas';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'persona_id', 'nombre', 'nit', 'numero_fundempresa', 'direccion', 'telefono'
    ];

    
    /**
     * Definicion de relacion .
     */
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }

    public function autorizaciones() 
    {
        return $this->hasMany(Autorizacion::class);
    }
    
}