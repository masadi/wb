<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rombongan_belajar extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'rombongan_belajar';
	protected $primaryKey = 'rombongan_belajar_id';
    protected $guarded = [];
    public function sekolah()
    {
        return $this->belongsTo('App\Sekolah', 'sekolah_id', 'sekolah_id');
    }
    public function jurusan_sp()
    {
        return $this->belongsTo('App\Jurusan_sp', 'jurusan_sp_id', 'jurusan_sp_id');
    }
}
