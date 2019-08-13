<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donacion extends Model
{
    use SoftDeletes;
    protected $table = 'donaciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'decomiso_id', 'resolucion_administrativa_id', 'beneficiario_donacion_id', 
        'hoja_ruta_id', 'numero_taques', 'destino', 'observaciones'
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

    public function hoja_ruta() 
    {
        return $this->belongsTo(HojaRuta::class);
    }

    public function beneficiario_donacion() 
    {
        return $this->belongsTo(BeneficiarioDonacion::class);
    }

}