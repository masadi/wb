<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Pakta_integritas extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'pakta_integritas';
	protected $primaryKey = 'pakta_integritas_id';
    protected $guarded = [];
    public function sekolah_sasaran(){
        return $this->belongsTo('App\Sekolah_sasaran', 'sekolah_sasaran_id', 'sekolah_sasaran_id');
    }
}
