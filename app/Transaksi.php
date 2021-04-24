<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = [];
    public function trader()
    {
        return $this->hasOne('App\Trader', 'id', 'trader_id');
    }
    public function trader_email()
    {
        return $this->hasOne('App\Trader', 'email', 'email');
    }
}
