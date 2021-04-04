<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Breakdown extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'breakdowns';
	protected $primaryKey = 'breakdown_id';
    protected $guarded = [];
    public function question(){
        return $this->hasMany('App\Question', 'breakdown_id', 'breakdown_id')->orderBy('urut', 'ASC');
    }
    public function breakdown_standar()
    {
        return $this->belongsTo('App\Breakdown_standar', 'breakdown_id', 'breakdown_id');
    }
}
