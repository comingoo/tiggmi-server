<?php
namespace App\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

use Carbon\Carbon;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //public $timestamps = false;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password','address', 'photo', 'dob', 'gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token', 'last_login_time', 'last_login_ip'
    ];

  
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
       // dd($token);
        $this->notify(new ResetPasswordNotification($token));
    }
}
