<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Federacion extends Model
{
    use SoftDeletes;
    protected $table = 'federaciones';
    public $timestamps = true;
    public $guarded = ['id'];
    protected $fillable = [
        'municipio_id', 'codigo', 'nombre', 'descripcion'
    ];

    public function municipio() 
    {
        return $this->belongsTo(Municipio::class);
    }
}