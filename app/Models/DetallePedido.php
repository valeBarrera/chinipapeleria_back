<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
   protected $table = 'detallepedido';
   public $timestamps=false;

   public function pedido(){
        return $this->belongsTo('App\Models\Pedido', 'Pedido_id');
   }
   public function producto(){
    return $this->belongsTo('App\Models\Producto', 'Producto_id');
   }

   public function configuracionFlashCard(){
    return $this->hasMany('App\Models\ConfiguracionFlashCard');
   }

}
