<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Tanah extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'tanah';
	protected $primaryKey = 'tanah_id';
    protected $guarded = [];
}
