<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Breakdown_standar extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'breakdown_standars';
	protected $primaryKey = 'breakdown_standar_id';
    protected $guarded = [];
    public function isi_standar()
    {
        return $this->belongsTo('App\Isi_standar', 'isi_standar_id', 'id');
    }
    public function question(){
        return $this->hasMany('App\Question', 'breakdown_id', 'breakdown_id')->orderBy('urut', 'ASC');
    }
}
