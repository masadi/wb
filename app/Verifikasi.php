<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Verifikasi extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'verifikasi';
	protected $primaryKey = 'verifikasi_id';
    protected $guarded = [];
}
