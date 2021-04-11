<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Angkutan extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'angkutan';
	protected $primaryKey = 'angkutan_id';
    protected $guarded = [];
    public function sekolah(){
        return $this->belongsTo('App\Sekolah', 'sekolah_id', 'sekolah_id');
    }
    public function kepemilikan(){
        return $this->belongsTo('App\Status_kepemilikan_sarpras', 'kepemilikan_sarpras_id', 'kepemilikan_sarpras_id');
    }
    public function jenis_sarana(){
        return $this->belongsTo('App\Jenis_sarana', 'jenis_sarana_id', 'id');
    }
}
