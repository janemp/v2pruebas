<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZonaAutorizada extends Model
{
    use SoftDeletes;
    protected $table = 'zona_autorizada';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'color'
    ];

    public function departamentos() 
    {
        return $this->hasMany(Departamento::class);
    }
}
