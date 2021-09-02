<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';
    public $timestamps=false;

    public function producto(){
        return $this->hasMany('App\Models\Producto');
    }
}
