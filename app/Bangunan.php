<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Bangunan extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'bangunan';
	protected $primaryKey = 'bangunan_id';
    protected $guarded = [];
    public function tanah(){
        return $this->belongsTo('App\Tanah', 'tanah_id', 'tanah_id');
    }
}
