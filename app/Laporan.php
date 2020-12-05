<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Laporan extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'laporan';
	protected $primaryKey = 'laporan_id';
    protected $guarded = [];
    public function berkas_laporan()
    {
        return $this->belongsTo('App\Berkas_laporan', 'laporan_id', 'laporan_id');
    }
    public function verifikator()
    {
        return $this->hasOne('App\User', 'user_id', 'verifikator_id');
    }
    public function pendamping()
    {
        return $this->hasOne('App\Pendamping', 'pendamping_id', 'pendamping_id');
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
}
