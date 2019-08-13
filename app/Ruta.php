<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruta extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    public $guarded = ['id'];
    protected $table = 'rutas';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'municipio_id', 'codigo', 'nombre', 'descripcion', 'origen_id', 'destino_id'
    ];

    
    public function municipio() 
    {
        return $this->belongsTo(Municipio::class);
    }

    public function puestos_de_control(){
        return $this->belongsToMany('App\PuestoControl', 'ruta_puesto')->orderBy('id');
    }

    public function origen(){
        return $this->belongsTo(PuestoControl::class);
    }

    public function destino(){
        return $this->belongsTo(PuestoControl::class);
    }
}
