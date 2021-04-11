<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mata_pelajaran extends Model
{
    protected $table = 'mata_pelajaran';
	protected $primaryKey = 'mata_pelajaran_id';
    protected $guarded = [];
    public function mapel(){
        return $this->belongsTo('App\Mapel', 'mata_pelajaran_id', 'mata_pelajaran_id');
    }
}
