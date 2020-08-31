<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Sekolah_sasaran extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'sekolah_sasaran';
	protected $primaryKey = 'sekolah_sasaran_id';
    protected $guarded = [];
    public function rapor_mutu(){
        return $this->hasOne('App\Rapor_mutu', 'sekolah_sasaran_id', 'sekolah_sasaran_id');
    }
    public function terkirim()
    {
        return $this->hasOne('App\Rapor_mutu', 'sekolah_sasaran_id', 'sekolah_sasaran_id')->whereHas('terkirim');
    }
    public function waiting()
    {
        return $this->hasOne('App\Rapor_mutu', 'sekolah_sasaran_id', 'sekolah_sasaran_id')->whereHas('waiting');
    }
    public function proses()
    {
        return $this->hasOne('App\Rapor_mutu', 'sekolah_sasaran_id', 'sekolah_sasaran_id')->whereHas('proses');
    }
    public function terima()
    {
        return $this->hasOne('App\Rapor_mutu', 'sekolah_sasaran_id', 'sekolah_sasaran_id')->whereHas('terima');
    }
    public function tolak()
    {
        return $this->hasOne('App\Rapor_mutu', 'sekolah_sasaran_id', 'sekolah_sasaran_id')->whereHas('tolak');
    }
    public function pakta_integritas(){
        return $this->hasOne('App\Pakta_integritas', 'sekolah_sasaran_id', 'sekolah_sasaran_id');
    }
    public function sekolah()
    {
        return $this->belongsTo('App\Sekolah', 'sekolah_id', 'sekolah_id');
    }
    public function berita_acara(){
        return $this->hasOne('App\Berita_acara', 'sekolah_sasaran_id', 'sekolah_sasaran_id');
    }
}
