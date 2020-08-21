<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Nilai_komponen extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'nilai_komponen';
	protected $primaryKey = 'nilai_komponen_id';
    protected $guarded = [];
    public function user(){
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }
}
