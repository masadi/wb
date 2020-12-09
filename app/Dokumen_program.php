<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dokumen_program extends Model
{
    protected $table = 'ref.dokumen_program';
	protected $guarded = [];
    public function program()
    {
        return $this->belongsTo('App\Program', 'program_id', 'id');
    }
    public function nilai_afirmasi()
    {
        return $this->hasOne('App\Nilai_afirmasi', 'dokumen_program_id', 'id');
    }
}
