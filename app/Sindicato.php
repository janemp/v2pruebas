<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sindicato extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    public $guarded = ['id'];
    protected $table = 'sindicatos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'central_id', 'codigo', 'nombre', 'descripcion'
    ];

    
    public function central() 
    {
        return $this->belongsTo(Central::class);
    }
}
