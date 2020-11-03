<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Nilai_dokumen extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'nilai_dokumen';
	protected $primaryKey = 'nilai_dok_id';
    protected $guarded = [];    
}
