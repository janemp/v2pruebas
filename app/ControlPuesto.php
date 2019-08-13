<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlPuesto extends Model
{
    protected $table = 'control_puesto';
    protected $fillable = ['control_id', 'puesto_id', 'user_id'];

    public function control_hoja_ruta(){
        return $this->belongsTo(ControlHojaRuta::class);
    }

    public function puesto_control(){
        return $this->belongsTo(PuestoControl::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
