<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlGuiaInternacion extends Model
{
    use SoftDeletes;
    protected $table = 'control_guias_internacion';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'guia_internacion_id', 'user_id'
    ];

    
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
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

}