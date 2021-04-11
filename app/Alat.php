<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
class Alat extends Model
{
    use Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'alat';
	protected $primaryKey = 'alat_id';
    protected $guarded = [];
    public function ruang(){
        return $this->belongsTo('App\Ruang', 'ruang_id', 'ruang_id');
    }
    /**
     * Get the user that owns the Alat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kepemilikan()
    {
        return $this->belongsTo(Status_kepemilikan_sarpras::class, 'kepemilikan_sarpras_id', 'kepemilikan_sarpras_id');
    }
    public function jenis_sarana()
    {
        return $this->belongsTo(Jenis_sarana::class, 'jenis_sarana_id', 'id');
    }
}
