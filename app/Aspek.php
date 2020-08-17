<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aspek extends Model
{
    protected $table = 'aspek';
    protected $guarded = [];
    public function komponen(){
        return $this->belongsTo('App\Komponen');
    }
    public function atribut(){
        return $this->hasMany('App\Atribut', 'aspek_id', 'id');
    }
}
