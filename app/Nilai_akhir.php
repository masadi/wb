<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Nilai_akhir extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'nilai_akhir';
	protected $primaryKey = 'nilai_akhir_id';
    protected $guarded = [];
}
