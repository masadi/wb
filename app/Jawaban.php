<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Jawaban extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'jawaban';
	protected $primaryKey = 'jawaban_id';
    protected $guarded = [];
}
