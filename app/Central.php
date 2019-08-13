<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Central extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    public $guarded = ['id'];
    protected $table = 'centrales';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'municipio_id', 'codigo', 'nombre', 'descripcion'
    ];

    
    public function municipio() 
    {
        return $this->belongsTo(Municipio::class);
    }
}
