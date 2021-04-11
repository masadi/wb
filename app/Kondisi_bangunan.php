<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CompositeKey;

class Kondisi_bangunan extends Model
{
    use CompositeKey;
    protected $table = 'kondisi_bangunan';
	protected $primaryKey = ['bangunan_id', 'tahun_pendataan_id'];
    protected $guarded = [];
}
