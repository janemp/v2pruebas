<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecomisoRetencion extends Model
{
    use SoftDeletes;
    protected $table = 'decomisos_retenciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'carnet_identidad',
        'id_retencion', 'carnet_productor', 'iniciales_embalaje_productor', 'carnet_comercializador',
        'iniciales_embalaje_comercializador', 'causa_retencion_id', 'lugar', 'fecha_hora', 'localidad_id',
        'municipio_id', 'provincia_id', 'departamento_id', 'descripcion_hecho', 'cantidad_taques_bultos', 'libras', 
        'terrestre_placa', 'marca_id', 'vehiculo_id', 'aereo_numero_vuelo', 'aereo_empresa', 'aereo_otros',
        'fluvial_nombre_embarcacion', 'embarcacion_id', 'fluvial_numero_registro', 'responsable_id', 
        'guia_internacion_id', 'hoja_ruta_id', 'decomisado' 
    ];

    
    /**
     * Definicion de relacion.
     */
    public function guias_internacion()
    {
        return $this->belongsToMany(GuiaInternacion::class);
    }

    /**
     * Definicion de relacion.
     */
    public function hojas_ruta()
    {
        return $this->belongsToMany(HojaRuta::class);
    }

    /**
     * Definicion de relacion.
     */
    public function causa_retencion()
    {
        return $this->belongsTo(CausaRetencion::class);
    }

    /**
     * Definicion de relacion.
     */
    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }
    
    /**
     * Definicion de relacion.
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Definicion de relacion.
     */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    /**
     * Definicion de relacion.
     */
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }
    
    /**
     * Definicion de relacion.
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Definicion de relacion.
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
    
    /**
     * Definicion de relacion.
     */
    public function embarcacion()
    {
        return $this->belongsTo(Embarcacion::class);
    }

    /**
     * Definicion de relacion.
     */
    public function responsable()
    {
        return $this->belongsTo(Responsable::class);
    }

    /**
     * Definicion de relacion.
     */
    public function guia_internacion()
    {
        return $this->belongsTo(GuiaInternacion::class);
    }

    /**
     * Definicion de relacion.
     */
    public function hoja_ruta()
    {
        return $this->belongsTo(HojaRuta::class);
    }
}