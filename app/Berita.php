<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';
    protected $guarded = [];
    public function kategori(){
        return $this->belongsToMany('App\Kategori');
    }
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
