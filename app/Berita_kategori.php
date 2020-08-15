<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Berita_kategori extends Model
{
    protected $table = 'berita_kategori';
    protected $guarded = [];
    public function kategori(){
        return $this->hasOne('App\Kategori');
    }
}
