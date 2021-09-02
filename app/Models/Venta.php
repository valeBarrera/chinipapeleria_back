<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    public $timestamps=false;

    public function pedido(){
        return $this->hasOne('App\Models\Pedido');
    }

    public function medioPago(){
        return $this->belongsTo('App\Models\MedioPago','MedioPago_id');
    }
}
