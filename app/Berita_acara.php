<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Berita_acara extends Model
{
    use Uuid;
	public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'berita_acara';
	protected $primaryKey = 'berita_acara_id';
    protected $guarded = [];
    public function berita()
    {
        return $this->belongsTo('App\Jenis_berita', 'jenis_berita_id', 'id')->where('jenis', 'berita');
    }
}
