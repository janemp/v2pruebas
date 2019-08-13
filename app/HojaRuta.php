<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HojaRuta extends Model
{
    use SoftDeletes;
    protected $fillable = ['persona_id', 'codigo_comercializador', 
    'puesto_venta_id', 'taques', 'fecha_inicio', 'fecha_final', 'procedencia_id', 
    'destino_id', 'localidad_id', 'destino_coca', 'vehiculo_id', 'marca_id', 'numero_placa', 
    'nombre_conductor', 'ruta_id', 'estado', 'correlativo', 'control', 'user_id', 'color', 'retenido'];

    /**
     * Definicion de relacion con comercializadores.
     */
    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }

    /**
     * Definicion de relacion con vehiculos.
     */
    public function vehiculo() 
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Definicion de relacion con marcas.
     */
    public function marca() 
    {
        return $this->belongsTo(Marca::class);
    }

    public function departamento_extension() 
    {
        return $this->belongsTo(Departamento::class);
    }

    public function procedencia(){
        return $this->belongsTo(Departamento::class);
    }

    public function destino(){
        return $this->belongsTo(Departamento::class);
    }

    public function ruta(){
        return $this->belongsTo(Ruta::class);
    }

    public function localidad(){
        return $this->belongsTo(Localidad::class);
    }

    public function retencion() 
    {
        return $this->hasMany(Retencion::class);
    }

    public function puesto_venta(){
        return $this->belongsTo(PuestoVenta::class);
    }
}
