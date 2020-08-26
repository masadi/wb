<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Dokumen extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'dokumen';
	protected $primaryKey = 'dokumen_id';
    protected $guarded = [];
    public function berita_acara()
    {
        return $this->belongsTo('App\Jenis_berita_acara', 'jenis_dokumen_id', 'id')->where('jenis', 'verifikasi');
    }
    public function pendukung()
    {
        return $this->belongsTo('App\Jenis_berita_acara', 'jenis_dokumen_id', 'id')->where('jenis', 'pendukung');
    }
}
