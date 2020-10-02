<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Wilayah extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use \Staudenmeir\EloquentHasManyDeep\HasTableAlias;
    use SoftDeletes;
    /*
    protected $dates = ['expired_date'];
    const DELETED_AT = 'expired_date';*/
    public $incrementing = false;
    protected $keyType = 'string';
	protected $table = 'wilayah';
    protected $primaryKey = 'kode_wilayah';
    public function parent()
    {
        return $this->belongsTo(Wilayah::class, 'mst_kode_wilayah', 'kode_wilayah');
    }
    public function parrentRecursive()
    {
        return $this->parent()->with('parrentRecursive');
    }
    public function negara(){
        return $this->hasOne('App\Negara', 'negara_id', 'negara_id');
    }
    public function child(){
        return $this->hasMany('App\Wilayah', 'mst_kode_wilayah', 'kode_wilayah');
    }
    public function sekolah(){
        return $this->hasMany('App\Sekolah', 'kode_wilayah', 'kode_wilayah');
    }
    public function desa(){
        //return $this->hasManyDeep('App\Sekolah', ['App\Wilayah', 'App\Wilayah']);
        return $this->hasManyDeep(
            'App\Wilayah',
            [
                'App\Wilayah as kabupaten', //wilayah1 
                'App\Wilayah as desa', //wilayah2
            ], // Intermediate models, beginning at the far parent (Country).
            [
               'mst_kode_wilayah', // Foreign key on the "wilayah1" table.
               'mst_kode_wilayah',    // Foreign key on the "wilayah2" table.
               'mst_kode_wilayah'     // Foreign key on the "Sekolah" table.
            ],
            [
              'kode_wilayah', // Local key on the "Wilayah" table.
              'kode_wilayah', // Local key on the "wilayah1" table.
              'kode_wilayah'  // Local key on the "wilayah1" table.
            ]
        );
    }
    public function sekolah_provinsi(){
        return $this->hasMany('App\Sekolah', 'provinsi_id', 'kode_wilayah');
    }
    public function sekolah_kabupaten(){
        return $this->hasMany('App\Sekolah', 'kabupaten_id', 'kode_wilayah');
    }
    public function sekolah_kecamatan(){
        return $this->hasMany('App\Sekolah', 'kecamatan_id', 'kode_wilayah');
    }
}
