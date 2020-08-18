<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
class Sekolah extends Model
{
    //use SoftDeletes;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'sekolah';
	protected $primaryKey = 'sekolah_id';
    protected $guarded = [];
    public function ptk(){
        return $this->hasMany('App\Ptk', 'sekolah_id', 'sekolah_id');
    }
    public function pd(){
        return $this->hasMany('App\Peserta_didik', 'sekolah_id', 'sekolah_id');
    }
}
