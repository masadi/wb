<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Telaah_dokumen extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'telaah_dokumen';
	protected $primaryKey = 'dok_id';
    protected $guarded = [];
    public function instrumen(){
        return $this->belongsTo('App\Instrumen');
    }
}