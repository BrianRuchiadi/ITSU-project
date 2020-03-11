<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IrsCity extends Model
{
    use SoftDeletes;

    public $table = 'irs_city';
    protected $fillable = [
        'CI_ID',
        'CI_Code',
        'CI_Description',
        'CI_State',
        'CI_Active_YN',
        'CI_DateCreated',
        'CI_CreatedBy',
        'CI_DateModified',
        'CI_ModifiedBy'
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
