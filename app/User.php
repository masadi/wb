<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
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
        'password', 'remember_token',
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
}
