<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atribut extends Model
{
    protected $table = 'atribut';
    protected $guarded = [];
    public function aspek(){
        return $this->belongsTo('App\Aspek');
    }
    public function indikator(){
        return $this->hasMany('App\Indikator', 'atribut_id', 'id');
    }
}
