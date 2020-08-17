<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    protected $table = 'indikator';
    protected $guarded = [];
    public function atribut(){
        return $this->belongsTo('App\Atribut');
    }
    public function instrumen(){
        return $this->hasMany('App\Instrumen', 'indikator_id', 'id');
    }
}
