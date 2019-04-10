<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UserAudit extends Model
{
    protected $table = "user_audit";
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'activity', 'activity_time', 'ip_address', 'comments'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'activity_time'
    ];
}