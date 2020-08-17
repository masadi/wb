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
    public function indikator(){
        return $this->belongsTo('App\Indikator');
    }
    public function jawaban(){
        return $this->hasOne('App\Jawaban', 'instrumen_id', 'instrumen_id');
    }
    public function subs(){
        return $this->hasMany('App\Instrumen', 'ins_id', 'instrumen_id')->orderBy('urut', 'DESC');
    }
}
