<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Berkas_laporan extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'berkas_laporan';
	protected $primaryKey = 'berkas_laporan_id';
    protected $guarded = [];
}
