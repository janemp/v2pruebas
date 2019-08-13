<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devolucion extends Model
{
    use SoftDeletes;
    protected $table = 'devoluciones';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'deposito_id', 'resolucion_administrativa_id', 'numero_taques'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function deposito() 
    {
        return $this->belongsTo(Deposito::class);
    }

    public function resolucion_administrativa() 
    {
        return $this->belongsTo(ResolucionAdministrativa::class);
    }
}