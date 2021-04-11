<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CompositeKey;

class Kondisi_ruang extends Model
{
    use CompositeKey;
    protected $table = 'kondisi_ruang';
	protected $primaryKey = ['ruang_id', 'tahun_pendataan_id'];
    protected $guarded = [];
}
