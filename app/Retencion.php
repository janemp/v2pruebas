<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retencion extends Model
{
    use SoftDeletes;
    protected $table = 'retenciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'persona_id', 'guia_internacion_id', 'hoja_ruta_id', 'user_id', 'correlativo', 
        'inicial_embalaje', 'departamento_id', 'provincia_id', 'municipio_id',
        'localidad_id', 'lugar', 'descripcion', 'numero_taques', 'libras',  'numero_placa', 'marca_id', 
        'vehiculo_id', 'numero_vuelo', 'empresa_vuelo', 'otros_vuelo', 'nombre_embarcacion', 'embarcacion_id',
        'numero_embarcacion', 'responsable'
    ];

    
    /**
     * Definicion de relaciones.
     */

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function guia_internacion()
    {
        return $this->belongsTo(GuiaInternacion::class);
    }

    public function hoja_ruta()
    {
        return $this->belongsTo(HojaRuta::class);
    }
    
    public function causas_retencion()
    {
        return $this->belongsToMany(CausaRetencion::class, 'retencion_causas', 'retencion_id', 'causa_retencion_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function embarcacion()
    {
        return $this->belongsTo(Embarcacion::class);
    }

    public function depositos()
    {
        return $this->hasMany(Deposito::class);
    }
}