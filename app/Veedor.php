<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Veedor extends Model
{
    protected $table = 'veedores';
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

    public function incineraciones()
    {
        return $this->belongsToMany(Incineracion::class);
    }
    

}