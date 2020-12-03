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
    public function nilai_komponen(){
        return $this->hasOne('App\Nilai_komponen', 'komponen_id', 'id');
    }
    public function all_nilai_komponen(){
        return $this->hasMany('App\Nilai_komponen', 'komponen_id', 'id')->whereNull('verifikator_id');
    }
    public function all_nilai_komponen_verifikasi(){
        return $this->hasMany('App\Nilai_komponen', 'komponen_id', 'id')->whereNotNull('verifikator_id')->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
    }
    public function nilai_komponen_verifikasi(){
        return $this->hasOne('App\Nilai_komponen', 'komponen_id', 'id');
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
