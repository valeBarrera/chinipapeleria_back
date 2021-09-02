<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    public $timestamps=false;
    protected $table = 'agenda';

    public function producto(){
        return $this->belongsTo('App\Models\Producto','Producto_id');
    }
    public function tamanioHoja(){
        return $this->belongsTo('App\Models\TamanioHoja','TamanioHoja_id');
    }
    public function tipoHoja(){
        return $this->belongsTo('App\Models\TipoHoja','TipoHoja_id');
    }
    public function tipoTapa(){
        return $this->belongsTo('App\Models\TipoTapa','TipoTapa_id');
    }
    public function secciones(){
        return $this->belongsToMany('App\Models\Seccion','seccionagenda','Agenda_id', 'Seccion_id');
    }


}
