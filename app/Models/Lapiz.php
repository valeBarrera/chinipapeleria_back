<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapiz extends Model
{
    protected $table = 'lapiz';
    public $timestamps=false;

    public function producto(){
        return $this->belongsTo('App\Models\Producto', 'Producto_id');
    }

    public function tipopunta(){
        return $this->belongsTo('App\Models\TipoPunta', 'TipoPunta_id');
    }
}
