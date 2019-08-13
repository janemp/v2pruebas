<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoResolucion extends Model
{
    protected $table = 'tipos_resolucion';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];

}