<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IrsItemRentalOpt extends Model
{
    use SoftDeletes;

    public $table = 'irs_itemrentalopt';
    protected $fillable = [
        'id',
        'IR_ItemID',
        'IR_OptionKey',
        'IR_OptionDesc',
        'IR_UnitPrice'
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
