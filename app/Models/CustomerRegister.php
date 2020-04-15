<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerRegister extends Model
{
    use SoftDeletes;
    
    public $table = 'customerregister';
    protected $fillable = [
        'name',
        'email',
        'userid',
        'password',
        'icno',
        'telephoneno',
        'telcode',
        'status'        
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $timestamps = false;
}
