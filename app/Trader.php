<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    protected $guarded = [];
    /**
     * Get the Sub_ib associated with the Trader
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function upline()
    {
        return $this->hasOne('App\Upline', 'trader_id', 'id');
    }
    public function downline()
    {
        return $this->hasMany('App\Downline', 'trader_id', 'id');
    }
    /**
     * Get all of the afiliasi for the Trader
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function afiliasi()
    {
        return $this->hasMany('App\Trader', 'sub_ib_id', 'id');
    }
    public function transaksi()
    {
        return $this->hasMany('App\Transaksi', 'trader_id', 'id');
    }
}
