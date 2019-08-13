<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamento extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zona_productiva_id', 'codigo', 'nombre', 'descripcion', 'sigla'
    ];

    public function provincias() 
    {
        return $this->hasMany(Provincia::class);
    }

    public function zona_productiva(){
        return $this->belongsTo(ZonaProductiva::class);
    }

    public function mercados(){
        return $this->belongsTo(Mercado::class);
    }
}