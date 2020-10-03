<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use App\HelperModel;
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
    public function sekolah_sasaran(){
        return $this->hasOne('App\Sekolah_sasaran', 'sekolah_id', 'sekolah_id')->where('tahun_pendataan_id', HelperModel::tahun_pendataan());
    }
    public function smk_coe(){
        return $this->hasOne('App\Smk_coe', 'sekolah_id', 'sekolah_id')->where('tahun_pendataan_id', HelperModel::tahun_pendataan());
    }
    public function user(){
        return $this->hasOne('App\User', 'sekolah_id', 'sekolah_id');
    }
    public function nilai_instrumen()
    {
        return $this->hasManyThrough(
            'App\Nilai_instrumen',
            'App\User',
            'sekolah_id', // Foreign key on User table...
            'user_id', // Foreign key on Nilai_instrumen table...
            'sekolah_id', // Local key on Sekolah table...
            'user_id' // Local key on User table...
        );
    }
    public function berita_acara()
    {
        return $this->hasOneThrough(
            'App\Berita_acara',
            'App\Sekolah_sasaran',
            'sekolah_id', // Foreign key on Sekolah_sasaran table...
            'berita_acara.sekolah_sasaran_id', // Foreign key on Sekolah table...
            'sekolah_id', // Local key on Rapor_mutu table...
            'sekolah_sasaran.sekolah_sasaran_id' // Local key on Sekolah_sasaran table...
        );
    }
    public function tahun_pendataan()
    {
        //return $this->belongsTo('App\Tahun_pendataan', 'tahun_pendataan_id', 'tahun_pendataan_id');
        return $this->hasOneThrough(
            'App\Tahun_pendataan',
            'App\Sekolah_sasaran',
            'sekolah_id', // Foreign key on Sekolah_sasaran table...
            'tahun_pendataan_id', // Foreign key on Sekolah table...
            'sekolah_id', // Local key on Rapor_mutu table...
            'tahun_pendataan_id' // Local key on Sekolah_sasaran table...
        );
    }
    public function wilayah(){
        return $this->hasOne('App\Wilayah', 'kode_wilayah', 'kode_wilayah')->with('parrentRecursive');
    }
}
