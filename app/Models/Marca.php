<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marca';
    public $timestamps= false;

    public function producto(){
        return $this->hasMany('App\Models\Producto');
    }
}
