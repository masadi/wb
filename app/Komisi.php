<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
    protected $guarded = [];
    public function sub_ib()
    {
        return $this->hasOne('App\Sub_ib', 'sub_ib_id', 'id');
    }
    public function trader()
    {
        return $this->hasOne('App\Trader', 'trader_id', 'id');
    }
}
