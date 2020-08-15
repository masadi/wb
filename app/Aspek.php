<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aspek extends Model
{
    protected $table = 'aspek';
    protected $guarded = [];
    public function komponen(){
        return $this->hasOne('App\Komponen');
        //return $this->hasOne('App\Aspek', 'aspek_id', 'aspek_id');
    }
}
