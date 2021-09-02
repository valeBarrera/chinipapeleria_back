<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoHoja extends Model
{
    protected $table = 'tipohoja';
    public $timestamps = false;

    public function configuracioncuaderno(){
        return $this->hasMany('App\Models\ConfiguracionCuaderno');
    }

    public function planificador(){
        return $this->hasMany('App\Models\Planificador');
    }

    public function agenda(){
        return $this->hasMany('App\Models\Agenda');
    }
}
