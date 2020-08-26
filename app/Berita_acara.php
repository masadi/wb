<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Berita_acara extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'berita_acara';
	protected $primaryKey = 'berita_acara_id';
    protected $guarded = [];
    public function berita()
    {
        return $this->belongsTo('App\Jenis_berita', 'jenis_berita_id', 'id')->where('jenis', 'berita');
    }
    public function dokumen()
    {
        return $this->hasOne('App\Dokumen', 'jenis_dokumen_id', 'jenis_berita_id')->whereHas('berita_acara');
        return $this->hasOneThrough(
            'App\Dokumen',
            'App\Jenis_dokumen',
            'jenis_dokumen_id', // Foreign key on Sekolah_sasaran table...
            'id', // Foreign key on Sekolah table...
            'jenis_berita_id', // Local key on Rapor_mutu table...
            'id' // Local key on Sekolah_sasaran table...
        );
    }
}
