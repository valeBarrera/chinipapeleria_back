<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashCard extends Model
{
    protected $table = 'flashcard';
    public $timestamps=false;

    public function producto(){
        return $this->belongsTo('App\Models\Producto', 'Producto_id');
    }
    public function configuracionFlashCard(){
        return $this->hasMany('App\Models\ConfiguracionFlashCard');
    }
}
