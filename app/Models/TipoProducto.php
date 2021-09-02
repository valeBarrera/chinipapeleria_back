<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    protected $table = 'tipoproducto';
    public $timestamps=false;

    public function producto(){
        return $this->hasMany('App\Models\Producto');
    }
}
