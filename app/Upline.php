<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upline extends Model
{
    protected $guarded = [];
    public function trader()
    {
        return $this->belongsTo('App\Trader', 'trader_id', 'id');
    }
}
