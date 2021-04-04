<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Nilai_standar extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'nilai_standar';
	protected $primaryKey = 'nilai_standar_id';
    protected $guarded = [];
}
