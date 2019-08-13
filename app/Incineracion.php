<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incineracion extends Model
{
    use SoftDeletes;
    protected $table = 'incineraciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'resolucion_administrativa_id', 'motivo_incineracion_id', 'numero_taques', 'lugar',
        'plazo', 'destino', 'autorizacion', 'transporte', 'observaciones'
    ];

    /**
     * Definicion de relaciones.
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function resolucion_administrativa() 
    {
        return $this->belongsTo(ResolucionAdministrativa::class);
    }
    
    public function motivo_incineracion() 
    {
        return $this->belongsTo(MotivoIncineracion::class);
    }
    
    public function responsables()
    {
        return $this->belongsToMany(Responsable::class, 'incineracion_responsables');
    }

}