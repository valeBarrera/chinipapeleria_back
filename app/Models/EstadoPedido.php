<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    protected $table = 'estadopedido';
    public $timestamps=false;

    public function pedido(){
        return $this->hasMany('App\Models\Pedido');
    }
}
