<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Rapor_mutu extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'rapor_mutu';
    protected $primaryKey = 'rapor_mutu_id';
    protected $guarded = [];
    public function jenis_rapor()
    {
        return $this->belongsTo('App\Jenis_rapor', 'jenis_rapor_id', 'id');
    }
    public function status_rapor()
    {
        return $this->belongsTo('App\Status_rapor', 'status_rapor_id', 'id');
    }
    public function waiting()
    {
        return $this->belongsTo('App\Status_rapor', 'status_rapor_id', 'id')->where('status', 'waiting');
    }
    public function proses()
    {
        return $this->belongsTo('App\Status_rapor', 'status_rapor_id', 'id')->where('status', 'proses');
    }
    public function terima()
    {
        return $this->belongsTo('App\Status_rapor', 'status_rapor_id', 'id')->where('status', 'terima');
    }
    public function tolak()
    {
        return $this->belongsTo('App\Status_rapor', 'status_rapor_id', 'id')->where('status', 'tolak');
    }
    public function sekolah()
    {
        return $this->hasOneThrough(
            'App\Sekolah',
            'App\Sekolah_sasaran',
            'sekolah_sasaran_id', // Foreign key on Sekolah_sasaran table...
            'sekolah_id', // Foreign key on Sekolah table...
            'sekolah_sasaran_id', // Local key on Rapor_mutu table...
            'sekolah_id' // Local key on Sekolah_sasaran table...
        );
    }
    public function berita_acara()
    {
        return $this->hasOne('App\Berita_acara', 'rapor_mutu_id', 'rapor_mutu_id')->whereHas('berita');
    }
    public function dokumen_pendukung()
    {
        //return $this->hasMany('App\Dokumen', 'rapor_mutu_id', 'rapor_mutu_id')->whereHas('pendukung');
        return $this->hasManyThrough(
            'App\Dokumen',
            'App\Berita_acara',
            'rapor_mutu_id', // Foreign key on User table...
            'berita_acara_id', // Foreign key on Nilai_instrumen table...
            'rapor_mutu_id', // Local key on Sekolah table...
            'berita_acara_id' // Local key on User table...
        )->whereHas('pendukung');
    }
    public function penjamin_mutu()
    {
        return $this->hasOne('App\User', 'user_id', 'verifikator_id');
    }
}
