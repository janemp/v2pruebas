<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poder extends Model
{
    use SoftDeletes;
    protected $table = 'poderes';
    protected $fillable = [
        'poder_id', 'persona_id', 'motivo', 'adjuntos', 'user_id'
    ];

    public function persona(){
        return $this->belongsTo('App\Persona');
    }

    public function poder(){
        return $this->belongsTo('App\Persona');
    }
}
