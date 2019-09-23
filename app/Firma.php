<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Firma extends Model
{
    use SoftDeletes;
    protected $fillable = ['nombre', 'cargo', 'img'];


}
