<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemParamDetail extends Model
{
    use SoftDeletes;
    
    public $table = 'systemparamdetail';
    protected $fillable = [
        'sysparam_id',
        'sysparam_cd',
        'param_val',
        'param_desc',
        'param_default',
    ];

    protected $hidden = [
        'usr_created',
        'created_at',
        'usr_updated',
        'updated_at',
        'usr_deleted',
        'deleted_at',
    ];
    public $timestamps = false;
}
