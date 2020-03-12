<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IrsState extends Model
{
    use SoftDeletes;
    //

    public $table = 'irs_state';
    protected $fillable = [
        'ST_ID',
        'ST_Code',
        'ST_Description',
        'ST_Active_YN',
        'ST_DateCreated',
        'ST_CreatedBy',
        'ST_DateModified',
        'ST_ModifiedBy',
        'ST_Country'
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
