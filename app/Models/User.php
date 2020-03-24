<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;
    
    public $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 
        'userid', 
        'password', 
        'name', 
        'telephone',
        'remember_token', 
        'branchind', 
        'status', 
        'login_attempt', 
        'password_reset',
        'acc_customer_module',
        'acc_contract_module',
        'acc_pos_module'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password_changed_at', 'last_login_attempt', 'usr_created', 'created_at', 'usr_updated', 'updated_at', 'usr_deleted', 'deleted_at'
    ];

    public $timestamps = false;
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
}
