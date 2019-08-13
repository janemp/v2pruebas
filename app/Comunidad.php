<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    protected $table = 'comunidades';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'municipio_id', 'nombre', 
    ];

    
    /**
     * Definicion de relacion con productores.
     */
    public function municipioi() 
    {
        return $this->belongsTo(Municipio::class);
    }

}