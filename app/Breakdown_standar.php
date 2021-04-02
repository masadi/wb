<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Breakdown_standar extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'breakdown_standars';
	protected $primaryKey = 'breakdown_standar_id';
    protected $guarded = [];
}
