<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Rapor_mutu extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'rapor_mutu';
    protected $primaryKey = 'rapor_mutu_id';
    protected $guarded = [];
    public function jenis_rapor()
    {
        return $this->belongsTo('App\Jenis_rapor', 'jenis_rapor_id', 'id');
    }
    public function status_rapor()
    {
        return $this->belongsTo('App\Status_rapor', 'status_rapor_id', 'id');
    }
}
