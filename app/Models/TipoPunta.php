<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPunta extends Model
{
    protected $table = 'tipopunta';
    public $timestamps = false;

    public function lapiz(){
        return $this->hasMany('App\Models\Lapiz');
    }
}
