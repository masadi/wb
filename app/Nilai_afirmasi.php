<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Nilai_afirmasi extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'nilai_afirmasi';
	protected $primaryKey = 'nilai_afirmasi_id';
    protected $guarded = [];
}
