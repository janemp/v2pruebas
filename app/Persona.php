<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;
    protected $table = 'personas';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_persona_id', 'nombre', 'primer_apellido', 'segundo_apellido', 'carnet_identidad', 'departamento_extension_id',
        'estado_civil', 'genero', 'telefono', 'email', 'domicilio', 'fecha_nacimiento', 'pais_nacimiento_id',
        'departamento_nacimiento_id', 'provincia_nacimiento_id', 'fotografia', 'biometrico', 'carnet_escaneado',
        'sustitucion_id', 'persona_id', 'adjunto_sustitucion', 'cesacion_id', 'adjunto_cesacion', 'activo', 'user_id'
    ];
    
    /**
     * Definicion de relaciones.
     */
    public function tipo_persona() 
    {
        return $this->belongsTo(TipoPersona::class);
    }

    public function departamento_extension() 
    {
        return $this->belongsTo(Departamento::class);
    }

    public function pais_nacimiento() 
    {
        return $this->belongsTo(Pais::class);
    }

    public function departamento_nacimiento() 
    {
        return $this->belongsTo(Departamento::class);
    }

    public function provincia_nacimiento() 
    {
        return $this->belongsTo(Provincia::class);
    }

    public function persona() 
    {
        return $this->belongsTo(Persona::class);
    }

    public function sustitucion() 
    {
        return $this->belongsTo(Sustitucion::class);
    }

    public function cesaciones() 
    {
        return $this->belongsToMany(Cesacion::class, 'persona_cesacion', 'persona_id', 'cesacion_id')->withPivot('id', 'adjunto_cesacion')->wherePivot('activo', true);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carnet_productor() 
    {
        return $this->hasMany(CarnetProductor::class)->where('renovado', false);
    }
    public function carnet_comercializador() 
    {
        return $this->hasMany(CarnetComercializador::class)->where('renovado', false);
    }

    public function poder(){
        return $this->hasMany(Persona::class);
    }

    public function resoluciones_administrativas(){
        return $this->hasMany(ResolucionAdministrativa::class);
    }

    public function puestos_venta(){
        return $this->belongsToMany(PuestoVenta::class);
    }
}