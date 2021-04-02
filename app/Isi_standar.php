<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Isi_standar extends Model
{
    protected $table = 'ref.isi_standar';
    protected $guarded = [];
    public function standar()
    {
        return $this->belongsTo('App\Standar', 'standar_id', 'id');
    }
}
