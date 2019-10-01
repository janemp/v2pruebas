<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutorizacionRenovacion extends Model
{
    use SoftDeletes;
    protected $table = 'autorizacion_renovaciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'informe_adjunto', 'observaciones','verificacion_destruccion', 
    ];    
    /**
     * Definicion de relacion con productores.
     */
    public function personaparcela() 
    {
        return $this->belongsTo(PersonaParcela::class);
    }
    
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }    
}
