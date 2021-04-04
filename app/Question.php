<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Question extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'questions';
	protected $primaryKey = 'question_id';
    protected $guarded = [];
    public function answer(){
        return $this->hasMany('App\Answer', 'question_id', 'question_id')->orderBy('urut', 'ASC');
    }
    public function breakdown()
    {
        return $this->belongsTo('App\Breakdown', 'breakdown_id', 'breakdown_id');
    }
}
