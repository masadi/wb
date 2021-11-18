<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    protected $guarded = [];
    protected $appends = ['DownlineCount'];
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
    public function DownlineCount(){
        return $this->hasOne('App\Downline', 'trader_id', 'id')->selectRaw('trader_id, count(*) as aggregate')->groupBy('trader_id');
    }
    public function getDownlineCountAttribute()
    {
        if ( ! array_key_exists('DownlineCount', $this->relations)) $this->load('DownlineCount');
        return ($this->getRelation('DownlineCount')) ? $this->getRelation('DownlineCount')->aggregate : 0;
    }
    public function afiliasi()
    {
        return $this->hasMany('App\Trader', 'sub_ib_id', 'id');
    }
    public function transaksi()
    {
        return $this->hasMany('App\Transaksi', 'email', 'email');
    }
    public function transaksi_downline(){
        return $this->hasOneThrough(
            'App\Transaksi', 
            'App\Downline',
            'trader_id', // Foreign key on cars table...
            'trader_id', // Foreign key on owners table...
            'id', // Local key on mechanics table...
            'downline_id' // Local key on cars table...
            //((laba_ib/12)*komisi) * dollar
        )
        ->selectRaw("ROUND(((SUM(laba_ib)/12) * downlines.komisi) * dollar,2) as total_komisi")
        ->groupBy('downlines.trader_id')->groupBy('dollar')->groupBy('downlines.komisi');
    }
    /*
    $all_data = Transaksi::whereHas('trader_email', function($query){
            $query->whereNotNull('email');
            $query->whereHas('downline');
        })
        ->with(['trader_email' => function($query){
            $query->with(['upline.trader']);
        }])
        ->selectRaw('email')
        ->selectRaw('tanggal_upload')
        ->selectRaw("SUM(volume) as volume_trading")
        //->selectRaw("ROUND(SUM(laba_ib),2) as sum_laba_ib")
        ->selectRaw("ROUND(SUM(laba_ib) * dollar,2) as sum_laba_ib")
        ->selectRaw("SUM(rebate) as jumlah_rebate")
        ->groupBy('dollar')
        ->groupBy('email')
        ->groupBy('tanggal_upload')
    */
}
