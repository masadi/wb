<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anggota_rombel extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'anggota_rombel';
	protected $primaryKey = 'anggota_rombel_id';
    protected $guarded = [];
    public function sekolah()
    {
        return $this->belongsTo('App\Sekolah', 'sekolah_id', 'sekolah_id');
    }
    public function rombongan_belajar()
    {
        return $this->belongsTo('App\Rombongan_belajar', 'rombongan_belajar_id', 'rombongan_belajar_id');
    }
    public function peserta_didik()
    {
        return $this->belongsTo('App\Peserta_didik', 'peserta_didik_id', 'peserta_didik_id');
    }
}
