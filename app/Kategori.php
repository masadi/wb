<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $guarded = [];
    public function berita(){
        return $this->belongsToMany('App\Berita');
    }
}
