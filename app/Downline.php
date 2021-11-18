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
    public function transaksi()
    {
        return $this->hasMany('App\Transaksi', 'trader_id', 'downline_id');
    }
}
