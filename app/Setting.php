<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
	protected $primaryKey = 'key';
    protected $guarded = [];
}
