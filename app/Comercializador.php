<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comercializador extends Model
{
    use SoftDeletes;
    protected $table = 'comercializadores';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'primer_apellido', 'segundo_apellido', 'carnet_identidad', 'departamento_extension_id',
        'estado_civil', 'genero', 'telefono', 'email', 'fecha_nacimiento', 'pais_nacimiento_id', 
        'departamento_nacimiento_id', 'provincia_nacimiento_id', 'domicilio', 'fotografia', 'biometrico',
        'carnet_escaneado', 'sustituido', 'comercializador_id', 'motivo_sustitucion', 'adjuntos_sustitucion',
        'cesado', 'motivo_cesacion', 'adjuntos_cesacion'
    ];

    public function poderes(){
        return $this->hasMany('App\Poder');
    }
    
    /**
     * Definicion de relacion de extension con departamentos.
     */
    public function departamento_extension() 
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Definicion de relacion con paises.
     */
    public function pais_nacimiento() 
    {
        return $this->belongsTo(Pais::class);
    }

    /**
     * Definicion de relacion con departamentos.
     */
    public function departamento_nacimiento() 
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Definicion de relacion con provincias.
     */
    public function provincia_nacimiento() 
    {
        return $this->belongsTo(Provincia::class);
    }

    /**
     * Definicion de relacion con comercializador.
     */
    public function comercializador() 
    {
        return $this->belongsTo(Comercializador::class);
    }
}