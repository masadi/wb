<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'ref.program';
    protected $guarded = [];
    public function dokumen_program(){
        return $this->HasMany('App\Dokumen_program', 'program_id', 'id');
    }
}
