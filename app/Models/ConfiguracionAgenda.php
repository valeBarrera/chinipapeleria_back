<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionAgenda extends Model
{
    protected $table = 'configuracionagenda';
    public $timestamps = false;

    public function detallepedido(){
        return $this->belongsTo('App\Models\DetallePedido', 'DetallePedido_id');
    }

    public function colorespiral(){
        return $this->belongsTo('App\Models\ColorEpiral', 'ColorEspiral_id');
    }

    public function agenda(){
        return $this->belongsTo('App\Models\Agenda', 'Agenda_id');
    }

    public function disenos(){
        return $this->belongsToMany('App\Models\Diseno','confagendadiseno','ConfiguracionAgenda_id', 'Diseno_id');
    }
}
