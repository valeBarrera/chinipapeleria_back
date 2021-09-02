<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuaderno extends Model
{
    public $timestamps=false;
    protected $table = 'cuaderno';

    public function producto(){
        return $this->belongsTo('App\Models\Producto', 'Producto_id');
    }
}
