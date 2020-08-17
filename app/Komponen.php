<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    protected $table = 'komponen';
    protected $guarded = [];
    public function jawaban(){
        return $this->hasMany('App\Jawaban', 'komponen_id', 'id');
    }
    public function aspek(){
        return $this->hasMany('App\Aspek', 'komponen_id', 'id')->withCount(['instrumen' => function($query){
            $query->where('urut', 0);
        }]);
    }
    public function indikator() {
        return $this->hasManyDeep(Indikator::class, [Aspek::class, Atribut::class]);
    }
}
