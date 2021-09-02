<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoLinea extends Model
{
    protected $table = 'tipolinea';
    public $timestamps = false;

    public function configuracionflashcard(){
        return $this->hasMany('App\Models\ConfiguracionFlashCard');
    }

    public function configuracioncuaderno(){
        return $this->hasMany('App\Models\ConfiguracionCuaderno');
    }
}
