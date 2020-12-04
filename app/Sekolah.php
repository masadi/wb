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
    public function anggota_rombel()
    {
        return $this->hasManyThrough(
            'App\Anggota_rombel',
            'App\Rombongan_belajar',
            'sekolah_id', // Foreign key on Rombongan_belajar table...
            'rombongan_belajar_id', // Foreign key on Anggota_rombel table...
            'sekolah_id', // Local key on sekolah table...
            'rombongan_belajar_id' // Local key on Rombongan_belajar table...
        );
    }
    public function jurusan_sp()
    {
        return $this->hasMany('App\Jurusan_sp', 'sekolah_id', 'sekolah_id');
    }
    public function guru(){
        return $this->hasMany('App\Ptk', 'sekolah_id', 'sekolah_id')->whereIn('jenis_ptk_id', [11,30,40,41,42,43,44,57,58,59]);
    }
    public function tendik(){
        return $this->hasMany('App\Ptk', 'sekolah_id', 'sekolah_id')->whereIn('jenis_ptk_id', [3,4,5,6,7,8,9,10,12,13,14,20,25,26,51,52,53,54,56]);
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
        return $this->hasOne('App\Wilayah', 'kode_wilayah', 'kode_wilayah')->withTrashed()->with('parrentRecursive')->where('id_level_wilayah', 4);
    }
    public function nilai_kinerja()
    {
        return $this->hasManyThrough(
            'App\Nilai_komponen',
            'App\User',
            'sekolah_id', // Foreign key on User table...
            'user_id', // Foreign key on Nilai_instrumen table...
            'sekolah_id', // Local key on Sekolah table...
            'user_id' // Local key on User table...
        )->whereIn('komponen_id', [1,2,3]);
    }
    public function nilai_kinerja_verifikasi(){
        return $this->nilai_kinerja();
    }
    public function nilai_dampak()
    {
        return $this->hasManyThrough(
            'App\Nilai_komponen',
            'App\User',
            'sekolah_id', // Foreign key on User table...
            'user_id', // Foreign key on Nilai_instrumen table...
            'sekolah_id', // Local key on Sekolah table...
            'user_id' // Local key on User table...
        )->whereIn('komponen_id', [4,5]);
    }
    public function nilai_dampak_verifikasi(){
        return $this->nilai_dampak();
    }
    public function pendamping()
    {
        return $this->hasOneThrough(
            'App\Pendamping',
            'App\Sekolah_sasaran',
            'sekolah_id', // Foreign key on Sekolah_sasaran table...
            'pendamping_id', // Foreign key on Sekolah table...
            'sekolah_id', // Local key on Rapor_mutu table...
            'pendamping_id' // Local key on Sekolah_sasaran table...
        );
    }
    public function nilai_input()
    {
        return $this->hasOneThrough(
            'App\Nilai_komponen',
            'App\User',
            'sekolah_id', // Foreign key on User table...
            'user_id', // Foreign key on Nilai_instrumen table...
            'sekolah_id', // Local key on Sekolah table...
            'user_id' // Local key on User table...
        )->where('komponen_id', 1);
    }
    public function nilai_input_verifikasi(){
        return $this->nilai_input();
    }
    public function nilai_proses()
    {
        return $this->hasOneThrough(
            'App\Nilai_komponen',
            'App\User',
            'sekolah_id', // Foreign key on User table...
            'user_id', // Foreign key on Nilai_instrumen table...
            'sekolah_id', // Local key on Sekolah table...
            'user_id' // Local key on User table...
        )->where('komponen_id', 2);
    }
    public function nilai_proses_verifikasi(){
        return $this->nilai_proses();
    }
    public function nilai_output()
    {
        return $this->hasOneThrough(
            'App\Nilai_komponen',
            'App\User',
            'sekolah_id', // Foreign key on User table...
            'user_id', // Foreign key on Nilai_instrumen table...
            'sekolah_id', // Local key on Sekolah table...
            'user_id' // Local key on User table...
        )->where('komponen_id', 3);
    }
    public function nilai_output_verifikasi(){
        return $this->nilai_output();
    }
    public function nilai_outcome()
    {
        return $this->hasOneThrough(
            'App\Nilai_komponen',
            'App\User',
            'sekolah_id', // Foreign key on User table...
            'user_id', // Foreign key on Nilai_instrumen table...
            'sekolah_id', // Local key on Sekolah table...
            'user_id' // Local key on User table...
        )->where('komponen_id', 4);
    }
    public function nilai_outcome_verifikasi(){
        return $this->nilai_outcome();
    }
    public function nilai_impact()
    {
        return $this->hasOneThrough(
            'App\Nilai_komponen',
            'App\User',
            'sekolah_id', // Foreign key on User table...
            'user_id', // Foreign key on Nilai_instrumen table...
            'sekolah_id', // Local key on Sekolah table...
            'user_id' // Local key on User table...
        )->where('komponen_id', 4);
    }
    public function nilai_impact_verifikasi(){
        return $this->nilai_impact();
    }
    public function nilai_akhir()
    {
        return $this->hasOneThrough(
            'App\Nilai_akhir',
            'App\User',
            'sekolah_id', // Foreign key on User table...
            'user_id', // Foreign key on Nilai_instrumen table...
            'sekolah_id', // Local key on Sekolah table...
            'user_id' // Local key on User table...
        );
    }
    public function nilai_akhir_verifikasi(){
        return $this->nilai_akhir();
    }
}
