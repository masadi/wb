<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Sekolah_sasaran extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'sekolah_sasaran';
	protected $primaryKey = 'sekolah_sasaran_id';
    protected $guarded = [];
}
