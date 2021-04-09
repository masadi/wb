<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Alat extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'alat';
	protected $primaryKey = 'alat_id';
    protected $guarded = [];
    public function ruang(){
        return $this->belongsTo('App\Ruang', 'ruang_id', 'ruang_id');
    }
}
