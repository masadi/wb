<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Rekap_pd extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'rekap_pd';
    protected $primaryKey = 'rekap_pd_id';
    protected $guarded = [];
}
