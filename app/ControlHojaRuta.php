<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlHojaRuta extends Model
{
    protected $table = 'control_hoja_rutas';
    protected $fillable = ['hoja_ruta_id', 'decomiso_retencion_id', 'decomisado', 'user_id'];

    public function hoja_ruta(){
        return $this->belongsTo(HojaRuta::class);
    }

    public function decomiso_retencion() 
    {
        return $this->belongsTo(DecomisoRetencion::class);
    }

    public function puestos_de_control(){
        return $this->hasMany(ControlPuesto::class);
    }
}
