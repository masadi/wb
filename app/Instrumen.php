<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Instrumen extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'instrumen';
	protected $primaryKey = 'instrumen_id';
    protected $guarded = [];
    public function aspek(){
        return $this->hasOne('App\Aspek');
        //return $this->hasOne('App\Aspek', 'aspek_id', 'aspek_id');
    }
}
