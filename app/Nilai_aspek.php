<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Nilai_aspek extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'nilai_aspek';
	protected $primaryKey = 'nilai_aspek_id';
    protected $guarded = [];
    public function aspek(){
        return $this->belongsTo('App\Aspek');
    }
    public function user(){
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }
}
