<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mercado extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    public $guarded = ['id'];
    public $table = 'mercados';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'departamento_id', 'codigo', 'nombre', 'descripcion', 'latitud', 'longitud', 'direccion'
    ];

    
    public function departamento() 
    {
        return $this->belongsTo(Departamento::class);
    }
}
