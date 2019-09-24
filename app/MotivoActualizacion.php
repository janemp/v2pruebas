<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoActualizacion extends Model
{
    protected $table = 'motivos_actualizacion';
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
     * Definicion de relacion.
     */
    public function Parcela() 
    {
        return $this->hasMany(Parcela::class);
    }
    

}