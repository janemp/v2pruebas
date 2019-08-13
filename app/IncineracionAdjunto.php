<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncineracionAdjunto extends Model
{
    protected $table = 'incineracion_adjuntos';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'incineracion_id', 'nombre'
    ];

    /**
     * Definicion de relacion.
     */
    public function incineracion() 
    {
        return $this->belongsTo(Incineracion::class);
    }
    

}