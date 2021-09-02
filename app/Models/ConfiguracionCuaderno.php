<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionCuaderno extends Model
{
    public $timestamps=false;
    protected $table = 'configuracioncuaderno';

    public function cuaderno(){
        return $this->belongsTo('App\Models\Cuaderno', 'Cuaderno_id');
    }
    public function detallepedido(){
        return $this->belongsTo('App\Models\DetallePedido', 'DetallePedido_id');
    }
    public function tipolinea(){
        return $this->belongsTo('App\Models\TipoLinea', 'TipoLinea_id');
    }
    public function tipohoja(){
        return $this->belongsTo('App\Models\TipoHoja', 'TipoHoja_id');
    }
    public function tipotapa(){
        return $this->belongsTo('App\Models\TipoTapa', 'TipoTapa_id');
    }
    public function tamaniohoja(){
        return $this->belongsTo('App\Models\TamanioHoja', 'TamanioHoja_id');
    }
    public function colorespiral(){
        return $this->belongsTo('App\Models\ColorEspiral', 'ColorEspiral_id');
    }
}
