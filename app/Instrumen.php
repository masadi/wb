<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Instrumen extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships, Uuid;
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
    public function jawaban_penjamin_mutu(){
        return $this->hasOne('App\Nilai_instrumen', 'instrumen_id', 'instrumen_id');
    }
    public function nilai_instrumen(){
        return $this->belongsTo('App\Nilai_instrumen', 'instrumen_id', 'instrumen_id')->orderBy('updated_at', 'ASC');
    }
    public function subs(){
        return $this->hasMany('App\Instrumen', 'ins_id', 'instrumen_id')->orderBy('urut', 'ASC');
    }
    public function aspek()
    {
        return $this->hasOneThrough(
            'App\Aspek', 
            'App\Jawaban',
            'instrumen_id', // Foreign key on cars table...
            'id', // Foreign key on owners table...
            'instrumen_id', // Local key on mechanics table...
            'aspek_id' // Local key on cars table...
        );
    }
}
