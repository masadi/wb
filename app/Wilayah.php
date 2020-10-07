<?php

namespace App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Wilayah extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use \Staudenmeir\EloquentHasManyDeep\HasTableAlias;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
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
    public function sekolah_instrumen_provinsi(){
        return $this->hasManyThrough(
            'App\User',
            'App\Sekolah',
            'provinsi_id', // Foreign key on users table...
            'sekolah_id', // Foreign key on posts table...
            'kode_wilayah', // Local key on countries table...
            'sekolah_id' // Local key on users table...
        )->whereHas('last_nilai_instrumen');
    }
    public function sekolah_instrumen_kabupaten(){
        return $this->hasManyThrough(
            'App\User',
            'App\Sekolah',
            'kabupaten_id', // Foreign key on users table...
            'sekolah_id', // Foreign key on posts table...
            'kode_wilayah', // Local key on countries table...
            'sekolah_id' // Local key on users table...
        )->whereHas('last_nilai_instrumen');
    }
    public function sekolah_instrumen_kecamatan(){
        return $this->hasManyThrough(
            'App\User',
            'App\Sekolah',
            'kecamatan_id', // Foreign key on users table...
            'sekolah_id', // Foreign key on posts table...
            'kode_wilayah', // Local key on countries table...
            'sekolah_id' // Local key on users table...
        )->whereHas('last_nilai_instrumen');
    }
    public function sekolah_rapor_mutu_provinsi(){
        return $this->hasManyThrough(
            'App\User',
            'App\Sekolah',
            'provinsi_id', // Foreign key on users table...
            'sekolah_id', // Foreign key on posts table...
            'kode_wilayah', // Local key on countries table...
            'sekolah_id' // Local key on users table...
        )->whereHas('nilai_akhir', function($query){
            $query->whereNull('verifikator_id');
        });
    }
    public function sekolah_rapor_mutu_kabupaten(){
        return $this->hasManyThrough(
            'App\User',
            'App\Sekolah',
            'kabupaten_id', // Foreign key on users table...
            'sekolah_id', // Foreign key on posts table...
            'kode_wilayah', // Local key on countries table...
            'sekolah_id' // Local key on users table...
        )->whereHas('nilai_akhir', function($query){
            $query->whereNull('verifikator_id');
        });
    }
    public function sekolah_rapor_mutu_kecamatan(){
        return $this->hasManyThrough(
            'App\User',
            'App\Sekolah',
            'kecamatan_id', // Foreign key on users table...
            'sekolah_id', // Foreign key on posts table...
            'kode_wilayah', // Local key on countries table...
            'sekolah_id' // Local key on users table...
        )->whereHas('nilai_akhir', function($query){
            $query->whereNull('verifikator_id');
        });
    }
    public function sekolah_pakta_integritas_provinsi(){
        return $this->hasManyThrough(
            'App\Sekolah_sasaran',
            'App\Sekolah',
            'provinsi_id', // Foreign key on users table...
            'sekolah_id', // Foreign key on posts table...
            'kode_wilayah', // Local key on countries table...
            'sekolah_id' // Local key on users table...
        )->whereHas('pakta_integritas');
    }
    public function sekolah_pakta_integritas_kabupaten(){
        return $this->hasManyThrough(
            'App\Sekolah_sasaran',
            'App\Sekolah',
            'kabupaten_id', // Foreign key on users table...
            'sekolah_id', // Foreign key on posts table...
            'kode_wilayah', // Local key on countries table...
            'sekolah_id' // Local key on users table...
        )->whereHas('pakta_integritas');
    }
    public function sekolah_pakta_integritas_kecamatan(){
        return $this->hasManyThrough(
            'App\Sekolah_sasaran',
            'App\Sekolah',
            'kecamatan_id', // Foreign key on users table...
            'sekolah_id', // Foreign key on posts table...
            'kode_wilayah', // Local key on countries table...
            'sekolah_id' // Local key on users table...
        )->whereHas('pakta_integritas');
    }
    public function sekolah_waiting_provinsi(){
        return $this->hasMany('App\Sekolah', 'provinsi_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('waiting');
        });
        return $this->hasManyDeep(
            'App\Rapor_mutu',
            [
                'App\Sekolah',
                'App\Sekolah_sasaran',
            ], // Intermediate models, beginning at the far parent (Country).
            [
               'provinsi_id', // Foreign key on the "Sekolah" table.
               'sekolah_id',    // Foreign key on the "Sekolah_sasaran" table.
               'sekolah_sasaran_id'     // Foreign key on the "Rapor_mutu" table.
            ],
            [
              'kode_wilayah', // Local key on the "Wilayah" table.
              'sekolah_id', // Local key on the "Sekolah" table.
              'sekolah_sasaran_id'  // Local key on the "Sekolah_sasaran" table.
            ]
        )->whereHas('waiting');
    }
    public function sekolah_waiting_kabupaten(){
        return $this->hasMany('App\Sekolah', 'kabupaten_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('waiting');
        });
    }
    public function sekolah_waiting_kecamatan(){
        return $this->hasMany('App\Sekolah', 'kecamatan_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('waiting');
        });
    }
    public function sekolah_proses_provinsi(){
        return $this->hasMany('App\Sekolah', 'provinsi_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('proses');
        });
        return $this->hasManyDeep(
            'App\Rapor_mutu',
            [
                'App\Sekolah',
                'App\Sekolah_sasaran',
            ], // Intermediate models, beginning at the far parent (Country).
            [
               'provinsi_id', // Foreign key on the "Sekolah" table.
               'sekolah_id',    // Foreign key on the "Sekolah_sasaran" table.
               'sekolah_sasaran_id'     // Foreign key on the "Rapor_mutu" table.
            ],
            [
              'kode_wilayah', // Local key on the "Wilayah" table.
              'sekolah_id', // Local key on the "Sekolah" table.
              'sekolah_sasaran_id'  // Local key on the "Sekolah_sasaran" table.
            ]
        )->whereHas('proses');
    }
    public function sekolah_proses_kabupaten(){
        return $this->hasMany('App\Sekolah', 'kabupaten_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('proses');
        });
    }
    public function sekolah_proses_kecamatan(){
        return $this->hasMany('App\Sekolah', 'kecamatan_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('proses');
        });
    }
    public function sekolah_terima_provinsi(){
        return $this->hasMany('App\Sekolah', 'provinsi_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('terima');
        });
        return $this->hasManyDeep(
            'App\Rapor_mutu',
            [
                'App\Sekolah',
                'App\Sekolah_sasaran',
            ], // Intermediate models, beginning at the far parent (Country).
            [
               'provinsi_id', // Foreign key on the "Sekolah" table.
               'sekolah_id',    // Foreign key on the "Sekolah_sasaran" table.
               'sekolah_sasaran_id'     // Foreign key on the "Rapor_mutu" table.
            ],
            [
              'kode_wilayah', // Local key on the "Wilayah" table.
              'sekolah_id', // Local key on the "Sekolah" table.
              'sekolah_sasaran_id'  // Local key on the "Sekolah_sasaran" table.
            ]
        )->whereHas('terima');
    }
    public function sekolah_terima_kabupaten(){
        return $this->hasMany('App\Sekolah', 'kabupaten_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('terima');
        });
    }
    public function sekolah_terima_kecamatan(){
        return $this->hasMany('App\Sekolah', 'kecamatan_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('terima');
        });
    }
    public function sekolah_tolak_provinsi(){
        return $this->hasMany('App\Sekolah', 'provinsi_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('tolak');
        });
        return $this->hasManyDeep(
            'App\Rapor_mutu',
            [
                'App\Sekolah',
                'App\Sekolah_sasaran',
            ], // Intermediate models, beginning at the far parent (Country).
            [
               'provinsi_id', // Foreign key on the "Sekolah" table.
               'sekolah_id',    // Foreign key on the "Sekolah_sasaran" table.
               'sekolah_sasaran_id'     // Foreign key on the "Rapor_mutu" table.
            ],
            [
              'kode_wilayah', // Local key on the "Wilayah" table.
              'sekolah_id', // Local key on the "Sekolah" table.
              'sekolah_sasaran_id'  // Local key on the "Sekolah_sasaran" table.
            ]
        )->whereHas('tolak');
    }
    public function sekolah_tolak_kabupaten(){
        return $this->hasMany('App\Sekolah', 'kabupaten_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('tolak');
        });
    }
    public function sekolah_tolak_kecamatan(){
        return $this->hasMany('App\Sekolah', 'kecamatan_id', 'kode_wilayah')->whereHas('sekolah_sasaran', function($query){
            $query->whereHas('tolak');
        });
    }
    public function sekolah_coe_provinsi(){
        return $this->hasMany('App\Sekolah', 'provinsi_id', 'kode_wilayah')->whereHas('smk_coe');//->whereHas('sekolah_sasaran');
    }
    public function sekolah_coe_kabupaten(){
        return $this->hasMany('App\Sekolah', 'kabupaten_id', 'kode_wilayah')->whereHas('smk_coe');//->whereHas('sekolah_sasaran');
    }
    public function sekolah_coe_kecamatan(){
        return $this->hasMany('App\Sekolah', 'kecamatan_id', 'kode_wilayah')->whereHas('smk_coe');//->whereHas('sekolah_sasaran');
    }
}
