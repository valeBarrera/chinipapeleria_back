<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planificador extends Model
{
    public $timestamps=false;
    protected $table = 'planificador';

    public function producto(){
        return $this->belongsTo('App\Models\Producto', 'Producto_id');
    }
    public function tipohoja(){
        return $this->belongsTo('App\Models\TipoHoja', 'TipoHoja_id');
    }
    public function tamaniohoja(){
        return $this->belongsTo('App\Models\TamanioHoja', 'TamanioHoja_id');
    }
    public function tipotapa(){
        return $this->belongsTo('App\Models\TipoTapa', 'TipoTapa_id');
    }
    public function colorespiral(){
        return $this->belongsTo('App\Models\ColorEspiral', 'ColorEspiral_id');
    }
}