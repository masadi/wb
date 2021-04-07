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
    public function nilai_akhir(){
        return $this->hasOne('App\Nilai_akhir', 'isi_standar_id', 'id');
    }
    public function instrumen_standar(){
        return $this->hasMany('App\Instrumen_standar', 'isi_standar_id', 'id');
    }
    public function sub_isi_standar(){
        return $this->hasMany('App\Isi_standar', 'isi_standar_id', 'id');
    }
    public function breakdown()
    {
        return $this->hasManyThrough(
            Breakdown::class,
            Breakdown_standar::class,
            'isi_standar_id', // Foreign key on the environments table...
            'breakdown_id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'breakdown_id' // Local key on the environments table...
        );
    }
}
