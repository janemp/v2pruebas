<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provincia extends Model
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
        'departamento_id', 'codigo', 'nombre', 'descripcion'
    ];

    public function municipios() 
    {
        return $this->hasMany(Municipio::class);
    }
    public function departamento() 
    {
        return $this->belongsTo(Departamento::class);
    }
}