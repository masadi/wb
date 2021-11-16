<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Downline extends Model
{
    protected $guarded = [];
    public function trader()
    {
        return $this->belongsTo('App\Trader', 'downline_id', 'id');
    }
}
