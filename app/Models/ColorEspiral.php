<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorEspiral extends Model
{
    protected $table = 'colorespiral';
    public $timestamps = false;

    public function tipoespiral(){
        return $this->belongsTo('App\Models\TipoEspiral', 'TipoEspiral_id');
    }

    public function configuracioncuaderno(){
        return $this->hasmany('App\Models\ConfiguracionCuaderno');
    }

    public function planificador(){
        return $this->hasmany('App\Models\Planificador');
    }

    public function configuracionagenda(){
        return $this->hasmany('App\Models\ConfiguracionAgenda');
    }
}
