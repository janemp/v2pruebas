<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PuestoControl extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    public $guarded = ['id'];
    protected $table = 'puestos_de_control';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'municipio_id', 'codigo', 'nombre', 'descripcion', 'latitud', 'longitud', 'direccion'
    ];

    
    public function municipio() 
    {
        return $this->belongsTo(Municipio::class);
    }

    public function rutas(){
        return $this->belongsToMany('App\Ruta', 'ruta_puesto');
    }
}
