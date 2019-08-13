<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CausaRetencion extends Model
{
    protected $table = 'causas_retencion';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 
    ];

    
    /**
     * Definicion de relacion con productores.
     */
    // public function causa_retenciones() 
    // {
    //     return $this->hasMany(DecomisoRetencion::class);
    // }

}