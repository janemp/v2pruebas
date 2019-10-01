<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parcela extends Model
{
    use SoftDeletes;
    public $fillable = ['fecha_parcela', 'codigo_catastral', 'comunidad_id', 'latitud', 'longitud', 'descripcion', 'hectareas', 'motivo_actualizacion_id', 'region_id', 'user_id','autorizacion_id'];

    public function personas(){
        return $this->belongsToMany(Persona::class, 'persona_parcela');
    }

    public function comunidad(){
        return $this->belongsTo(Comunidad::class);
    }

    public function motivo_actualizacion(){
        return $this->belongsTo(MotivoActualizacion::class);
    }

    public function region(){
        return $this->belongsTo(Regional::class);
    }

    public function personaparcela()
    {
    return $this->belongsTo(PersonaParcela::class);
    }

    public function autorizacionrenovacion()
    {
        return $this->belongsTo(AutorizacionRenovacion::class);
    }
 
}
