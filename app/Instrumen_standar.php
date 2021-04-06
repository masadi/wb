<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Instrumen_standar extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'instrumen_standar';
	protected $primaryKey = 'instrumen_standar_id';
    protected $guarded = [];
}
