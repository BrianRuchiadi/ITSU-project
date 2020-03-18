<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IrsItemUom extends Model
{
    use SoftDeletes;

    public $table = 'irs_itemuom';
    protected $fillable = [
        'id',
        'IU_ID',
        'IU_ItemID',
        'IU_UOM',
        'IU_Rate',
        'IU_SalesPrice1',
        'IU_SalesPrice2',
        'IU_SalesPrice3',
        'IU_SalesPrice4',
        'IU_SalesPrice5',
        'IU_SalesPrice6',
        'IU_MinSalesPrice',
        'IU_Barcode',
        'IU_ClientID',
        'IU_CreatedBy',
        'IU_DateCreated',
        'IU_ModifiedBy',
        'IU_DateModified',
        'IU_Created',
        'IU_Modified',
        'IU_IAGID',
        'IU_Active_YN',
        'IM_Code',
        'UOM_Code',
        'IsBaseUOM'
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
