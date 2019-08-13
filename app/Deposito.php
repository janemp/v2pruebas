<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposito extends Model
{
    use SoftDeletes;
    protected $table = 'depositos';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'retencion_id', 'devolucion_id', 'decomiso_id', 'donacion_id', 'incineracion_id',
        'correlativo', 'estado', 'numero_taques', 'observaciones'
    ];

    
    /**
     * Definicion de relaciones.
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function retencion() 
    {
        return $this->belongsTo(Retencion::class);
    }
    
    public function devoluciones() 
    {
        return $this->hasMany(Devolucion::class);
    }

    public function decomisos() 
    {
        return $this->hasMany(Decomiso::class);
    }
   
}