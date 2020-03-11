<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IrsCountry extends Model
{
    use SoftDeletes;

    public $table = 'irs_country';
    protected $fillable = [
        'CO_ID',
        'CO_Code',
        'CO_Description',
        'CO_Active_YN',
        'CO_DateCreated',
        'CO_CreatedBy',
        'CO_DateModified',
        'CO_ModifiedBy'
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
