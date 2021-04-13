<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_ib extends Model
{
    //protected $fillable = ['nama_lengkap', 'nomor_akun', 'email', 'telepon', 'bank', 'nomor_rekening', 'sub_ib'];
    protected $guarded = [];
    /**
     * Get the user associated with the Sub_ib
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function komisi()
    {
        return $this->hasMany('App\Komisi', 'sub_ib_id', 'id');
    }
    /**
     * Get the user that owns the Sub_ib
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trader()
    {
        return $this->belongsTo('App\Trader', 'trader_id', 'id');
    }
}
