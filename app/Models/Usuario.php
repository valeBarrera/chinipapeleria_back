<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    public $timestamps=false;

    public function pedido(){
        return $this->hasMany('App\Models\Pedido');
    }
}
