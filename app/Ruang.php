<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Ruang extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'ruang';
	protected $primaryKey = 'ruang_id';
    protected $guarded = [];
    public function bangunan(){
        return $this->belongsTo('App\Bangunan', 'bangunan_id', 'bangunan_id');
    }
}
