<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTapa extends Model
{
    protected $table = 'tipotapa';
    public $timestamps=false;

    public function agenda(){
        return $this->hasMany('App\Models\Agenda');
    }

    public function configuracioncuaderno(){
        return $this->hasMany('App\Models\ConfiguracionCuaderno');
    }

    public function planificador(){
        return $this->hasMany('App\Models\Planificador');
    }
}
