<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZonaProductiva extends Model
{
    use SoftDeletes;
    protected $table = 'zona_productiva';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'color'
    ];

    public function departamentos() 
    {
        return $this->hasMany(Departamento::class);
    }
}
