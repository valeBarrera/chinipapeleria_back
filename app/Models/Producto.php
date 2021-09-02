<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    const LAPIZ = 1;
    const CUADERNO = 2;
    const AGENDA = 3;
    const PLANIFICADOR = 4;
    const FLASHCARD = 5;

    protected $table = 'producto';
    public $timestamps=false;

    public function categoria(){
        return $this->belongsTo('App\Models\Categoria','Categoria_id');
    }

    public function tipoProducto(){
        return $this->belongsTo('App\Models\TipoProducto','TipoProducto_id');
    }

    public function marca(){
        return $this->belongsTo('App\Models\Marca','Marca_id');
    }

    public function detallePedido(){
        return $this->hasmany('App\Models\DetallePedido');
    }

    public function flashCard(){
        return $this->hasOne('App\Models\FlashCard');
    }
    public function lapiz(){
        return $this->hasOne('App\Models\Lapiz');
    }
}
