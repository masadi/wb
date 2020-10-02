<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Negara extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'negara';
    protected $primaryKey = 'negara_id';
}
