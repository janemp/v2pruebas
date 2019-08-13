<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productor extends Model
{
    use SoftDeletes;
    protected $table = 'productores';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'persona_id', 'departamento_id', 'provincia_id', 'municipio_id', 'regional_id', 'codigo_socio',
        'codigo'
    ];

    /**
     * Definicion de relaciones
     */
    public function persona() 
    {
        return $this->belongsTo(Personas::class);
    }

    public function departamento() 
    {
        return $this->belongsTo(Departamento::class);
    }

    public function provincia() 
    {
        return $this->belongsTo(Provincia::class);
    }

    public function municipio() 
    {
        return $this->belongsTo(Municipio::class);
    }

    public function regional() 
    {
        return $this->belongsTo(Regional::class);
    }
}