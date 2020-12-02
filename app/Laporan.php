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
}
