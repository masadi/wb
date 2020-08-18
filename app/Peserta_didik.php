<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peserta_didik extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'peserta_didik';
	protected $primaryKey = 'peserta_didik_id';
    protected $guarded = [];
}
