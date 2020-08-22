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
    public function pakta_integritas(){
        return $this->hasOne('App\Pakta_integritas', 'sekolah_sasaran_id', 'sekolah_sasaran_id');
    }
}
