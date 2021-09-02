<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionPlanificador extends Model
{
    protected $table = 'configuracionplanificador';
    public $timestamps = false;

    public function tipoplanificador(){
        return $this->belongsTo('App\Models\TipoPlanificador', 'TipoPlanificador_id');
    }
    public function detallepedido(){
        return $this->belongsTo('App\Models\DetallePedido', 'DetallePedido_id');
    }
    public function planificador(){
        return $this->belongsTo('App\Models\Planificador', 'Planificador_id');
    }
}