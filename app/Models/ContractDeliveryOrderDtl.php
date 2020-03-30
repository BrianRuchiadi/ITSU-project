<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractDeliveryOrderDtl extends Model
{
    use SoftDeletes;
    
    public $table = 'contractdeliveryorderdtl';
    protected $fillable = [
        'contractdeliveryorder_id',
        'CDOD_ItemID',
        'CDOD_Description',
        'CDOD_ItemUOMID',
        'CDOD_ItemTypeID',
        'CDOD_WarehouseID',
        'CDOD_BinLocationID',
        'CDOD_Qty',
        'CDOD_UnitPrice',
        'CDOD_SubTotal',
        'CDOD_TaxAmt',
        'CDOD_TaxableAmt',
        'CDOD_Total',
        'CDOD_SerialNo',
        'CDOD_Item_Seq',
        'cn_Item_Seq',
        'usr_created',
        'usr_updated',
        'usr_deleted'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',

    ];
    public $timestamps = false;
}
