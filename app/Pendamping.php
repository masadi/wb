<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\HelperModel;
class Pendamping extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'pendamping';
	protected $primaryKey = 'pendamping_id';
    protected $guarded = [];
    public function sekolah_sasaran()
    {
        return $this->hasManyThrough(
            'App\Sekolah',
            'App\Sekolah_sasaran',
            'pendamping_id', // Foreign key on Sekolah_sasaran table...
            'sekolah_id', // Foreign key on Sekolah table...
            'pendamping_id', // Local key on Rapor_mutu table...
            'sekolah_id' // Local key on Sekolah_sasaran table...
        )->where('tahun_pendataan_id', HelperModel::tahun_pendataan());
    }
}
