<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
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
        'provincia_id', 'codigo', 'nombre', 'descripcion'
    ];

    
    public function provincia() 
    {
        return $this->belongsTo(Provincia::class);
    }
}