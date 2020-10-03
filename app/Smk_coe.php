<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Smk_coe extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'smk_coe';
	protected $primaryKey = 'smk_coe_id';
    protected $guarded = [];
}
