<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEspiral extends Model
{
    protected $table = 'tipoespiral';
    public $timestamps = false;

    public function colorespiral(){
        return $this->hasMany('App\Models\ColorEspiral');
    }
}
