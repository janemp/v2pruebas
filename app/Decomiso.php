<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Decomiso extends Model
{
    use SoftDeletes;
    protected $table = 'decomisos';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'deposito_id', 'resolucion_administrativa_id', 'numero_taques'
    ];

    
    /**
     * Definicion de relacion .
     */
    public function retencion() 
    {
        return $this->belongsTo(Retencion::class);
    }

    public function deposito() 
    {
        return $this->belongsTo(Deposito::class);
    }
    
    public function resolucion_administrativa() 
    {
        return $this->belongsTo(ResolucionAdministrativa::class);
    }

    public function donaciones() 
    {
        return $this->hasMany(Donacion::class);
    }

    // public function incineraciones() 
    // {
    //     return $this->hasMany(Incineracion::class);
    // }

    // public function investigaciones() 
    // {
    //     return $this->hasMany(investigacion::class);
    // }
    
}