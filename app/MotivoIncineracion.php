<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoIncineracion extends Model
{
    protected $table = 'motivos_incineracion';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];

    /**
     * Definicion de relacion.
     */
    public function Veedores() 
    {
        return $this->hasMany(Veedor::class);
    }
    

}