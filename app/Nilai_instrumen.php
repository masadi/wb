<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Nilai_instrumen extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'nilai_instrumen';
	protected $primaryKey = 'nilai_instrumen_id';
    protected $guarded = [];
}
