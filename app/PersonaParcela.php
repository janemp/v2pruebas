<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class PersonaParcela extends Model
{
    use SoftDeletes;
    protected $table = 'persona_parcela';
    public $timestamps = true;
    public $guarded = ['id'];
    
    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'persona_id', 'parcela_id'
    ];

    public function autorizacionrenovacion()
    {
        return $this->belongsTo(AutorizacionRenovacion::class);
    }

    public function parcela()
    {
        return $this->belongsTo(Parcela::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

}
