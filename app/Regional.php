<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regional extends Model
{
    use SoftDeletes;
    protected $table = 'regionales';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'municipio_id'
    ];

    /**
     * Definicion de relacion con productores.
     */
    public function municipio(){
        return $this->belongsTo(Municipio::class);
    }

}