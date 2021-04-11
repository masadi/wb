<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Buku extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'buku';
	protected $primaryKey = 'buku_id';
    protected $guarded = [];
    public function sekolah(){
        return $this->belongsTo('App\Sekolah', 'sekolah_id', 'sekolah_id');
    }
    public function mata_pelajaran(){
        return $this->belongsTo('App\Mata_pelajaran', 'mata_pelajaran_id', 'mata_pelajaran_id');
    }
}
