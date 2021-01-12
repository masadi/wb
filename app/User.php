<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use App\HelperModel;
use App\Traits\Uuid;
class User extends Authenticatable
{
    use LaratrustUserTrait, Notifiable, Uuid;
    public $incrementing = false;
    protected $keyType = 'string';
	//protected $table = 'instrumens';
	protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [
        //'name', 'email', 'password', 'username',
    //];
    protected $guarded = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function sekolah(){
        return $this->belongsTo('App\Sekolah', 'sekolah_id', 'sekolah_id');
    }
    public function nilai_instrumen(){
        return $this->hasMany('App\Nilai_instrumen', 'user_id', 'user_id');
    }
    public function isian_instrumen(){
        return $this->hasMany('App\Nilai_instrumen', 'verifikator_id', 'user_id');
    }
    public function koreksi_instrumen(){
        return $this->hasMany('App\Nilai_instrumen', 'verifikator_id', 'user_id');
    }
    public function sekolah_sasaran(){
        return $this->hasMany('App\Sekolah_sasaran', 'verifikator_id', 'user_id');
    }
    public function last_nilai_instrumen(){
        return $this->hasOne('App\Nilai_instrumen', 'user_id', 'user_id')->orderBy('updated_at', 'DESC');
    }
    public function nilai_akhir(){
        return $this->hasOne('App\Nilai_akhir', 'user_id', 'user_id');//->where('tahun_pendataan_id', HelperModel::tahun_pendataan());
    }
    public function nilai_akhir_verifikasi(){
        return $this->hasOne('App\Nilai_akhir', 'user_id', 'user_id');//->where('tahun_pendataan_id', HelperModel::tahun_pendataan());
    }
    public function nilai_akhir_penjamin_mutu(){
        return $this->hasOne('App\Nilai_akhir', 'verifikator_id', 'user_id');//->where('tahun_pendataan_id', HelperModel::tahun_pendataan());
    }
    public function pakta_integritas(){
        return $this->hasOne('App\Pakta_integritas', 'user_id', 'user_id');
    }
    public function getTokenAttribute($value){
        return strtoupper($value);
    }
    public function jawaban(){
        return $this->hasMany('App\Jawaban', 'user_id', 'user_id')->whereNotNull('verifikator_id')->where('verifikator_id', '<>', '84ff9f29-1bd0-462f-976f-4c512dc22cc2');
    }
    public function jawaban_sekolah(){
        return $this->hasMany('App\Jawaban', 'user_id', 'user_id')->whereNull('verifikator_id');
    }
}
