<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tahun_pendataan extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'tahun_pendataan';
	protected $primaryKey = 'tahun_pendataan_id';
	protected $guarded = [];
}
