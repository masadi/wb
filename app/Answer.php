<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Answer extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'answers';
	protected $primaryKey = 'answer_id';
    protected $guarded = [];
    /**
     * Get the user associated with the Answer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nilai_answer()
    {
        return $this->hasOne('App\Nilai_answer', 'answer_id', 'answer_id');
    }
    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id', 'question_id');
    }
}
