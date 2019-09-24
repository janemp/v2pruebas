<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutorizacionRenovacion extends Model
{
    protected $table = 'autorizacion_renovaciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'informe_adjunto', 'observaciones','verificacion_destruccion','persona_parcela_id', 
    ];    
    /**
     * Definicion de relacion con productores.
     */
    public function personaparcela() 
    {
        return $this->ManytoMany(PersonaParcela::class);
    }
}
