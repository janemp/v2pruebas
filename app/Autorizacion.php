<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autorizacion extends Model
{
    use SoftDeletes;
    protected $table = 'autorizaciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'empresa_id', 'autorizacion', 'fecha_emision', 'fecha_conclusion', 'duplicado'
    ];

    
    /**
     * Definicion de relacion .
     */
    public function empresa() 
    {
        return $this->belongsTo(Empresa::class);
    }
    
}