<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parcela extends Model
{
    use SoftDeletes;
    public $fillable = ['fecha_parcela', 'codigo', 'comunidad_id', 'latitud', 'longitud', 'descripcion', 'hectareas', 'motivo_actualizacion_id'];

    public function personas(){
        return $this->ManytoMany(Persona::class);
    }
}
