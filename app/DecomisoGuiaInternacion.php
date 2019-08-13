<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DecomisoGuiaInternacion extends Model
{
    protected $table = 'decomisos_guia_internacion';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'id_retencion', 'carnet_productor', 'iniciales_embalaje_productor', 'carnet_comercializador',
        'iniciales_embalaje_comercializador', 'causa_retencion_id', 'lugar', 'fecha_hora', 'localidad_id',
        'municipio_id', 'departamento_id', 'descripcion_hecho', 'cantidad_taques_bultos', 'libras', 
        'terrestre_placa', 'marca_id', 'vehiculo_id', 'aereo_numero_vuelo', 'aereo_empresa', 'aereo_otros',
        'fluvial_nombre_embarcacion', 'embarcacion_id', 'fluvial_numero_registro', 'responsable_id' 
    ];

    
    /**
     * Definicion de relacion.
     */
    public function guias_internacion()
    {
        return $this->belongsToMany(GuiaInternacion::class);
    }

}