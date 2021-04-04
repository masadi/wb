<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan_sp extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'jurusan_sp';
    protected $primaryKey = 'jurusan_sp_id';
    protected $guarded = [];
    public function sekolah()
    {
        return $this->belongsTo('App\Sekolah', 'sekolah_id', 'sekolah_id');
    }
    public function rombongan_belajar(){
        return $this->hasMany('App\Rombongan_belajar', 'jurusan_sp_id', 'jurusan_sp_id');
    }
    public function peserta_didik()
    {
        return $this->hasManyThrough(
            Anggota_rombel::class,
            Rombongan_belajar::class,
            'jurusan_sp_id', // Foreign key on the environments table...
            'rombongan_belajar_id', // Foreign key on the deployments table...
            'jurusan_sp_id', // Local key on the projects table...
            'rombongan_belajar_id' // Local key on the environments table...
        );
    }
}
