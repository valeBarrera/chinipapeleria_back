<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MedioPago extends Model
{
    protected $table = 'mediopago';
    public $timestamps=false;

    public function Venta(){
        return $this->hasMany('App\Models\Venta');
    }
}
