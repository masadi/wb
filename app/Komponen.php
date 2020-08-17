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
    public function instrumen() {
        return $this->hasManyDeep(Instrumen::class, [Indikator::class, Atribut::class]);
    }
}
