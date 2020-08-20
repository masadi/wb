<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Jawaban extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'jawaban';
	protected $primaryKey = 'jawaban_id';
    protected $guarded = [];
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
}
