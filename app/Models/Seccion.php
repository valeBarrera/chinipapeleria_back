<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $table = 'seccion';
    public $timestamps=false;

    public function seccionagenda(){
        return $this->hasMany('App\Models\SeccionAgenda');
    }

    public function agendas(){
        return $this->belongsToMany('App\Models\Agenda','seccionagenda', 'Seccion_id','Agenda_id',);
    }
}
