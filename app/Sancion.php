<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Sancion extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    public $guarded = ['id'];
    protected $table = 'sanciones';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'infraccion_id', 'codigo', 'nombre', 'descripcion','monto','bloqueante','estado','adjunto'
    ];

    public function persona() 
    {
        return $this->hasMany(Persona::class);
    }

    public function infraccion()
    {
        return $this->belongsTo(Infraccion::class);
    }

    public function tipo_sancion() 
    {
        return $this->belongsTo(TipoSancion::class);
    }   

    public function comercializador_infraccion() 
    {
        return $this->belongsTo(ComercializadorInfraccion::class);
    }  

    public function comercializadorinfraccion()
    {
        return $this->belongsToMany(ComercializadorInfraccion::class, 'persona');
    }
  
}
