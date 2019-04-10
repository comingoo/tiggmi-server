<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{
    /**
     * Define primary key for delete() function works.
     */
    public $timestamps = false;
    protected $primaryKey = 'email';
    protected $fillable = [
        'email', 'token'
    ];
    protected $table = 'password_resets';
}
