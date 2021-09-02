<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionFlashCard extends Model
{
    public $timestamps = false;
    protected $table = 'configuracionflashcard';

    public function flashCard(){
        return $this->belongsTo('App\Models\FlashCard', 'FlashCard_id');
    }

    public function detallpePedido(){
        return $this->belongsTo('App\Models\DetallePedido', 'DetallePedido_id');
    }

    public function diseño(){
        return $this->belongsTo('App\Models\Diseno', 'Diseno_id');
    }

    public function TipoLinea(){
        return $this->belongsTo('App\Models\TipoLinea', 'TipoFlashCard_id');
    }
}
