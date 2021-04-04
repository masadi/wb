<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Isi_standar extends Model
{
    protected $table = 'ref.isi_standar';
    protected $guarded = [];
    public function standar()
    {
        return $this->belongsTo('App\Standar', 'standar_id', 'id');
    }
    public function nilai_standar(){
        return $this->hasOne('App\Nilai_standar', 'isi_standar_id', 'id');
    }
    public function breakdown_standar(){
        return $this->hasMany('App\Breakdown_standar', 'isi_standar_id', 'id');
    }
}
