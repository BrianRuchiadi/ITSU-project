<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerUserMap extends Model
{
    use SoftDeletes;
    
    public $table = 'customerusermap';
    protected $fillable = [
        'users_id',
        'customer_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $timestamps = false;
}
