<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Nilai_answer extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'nilai_answer';
	protected $primaryKey = 'nilai_answer_id';
    protected $guarded = [];
    public function answer()
    {
        return $this->belongsTo('App\Answer', 'answer_id', 'answer_id');
    }
}
