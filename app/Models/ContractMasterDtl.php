<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractMasterDtl extends Model
{
    use SoftDeletes;
    
    public $table = 'contractmasterdtl';
    protected $fillable = [
        'contractmast_id',
        'CND_ItemID',
        'CND_Description',
        'CND_ItemUOMID',
        'CND_ItemTypeID',
        'CND_Qty',
        'CND_UnitPrice',
        'CND_SubTotal',
        'CND_TaxAmt',
        'CND_TaxableAmt',
        'CND_Total',
        'CND_SerialNo',
        'CND_ItemSeq',
        'CND_WarehouseID',
        'CND_BinLocationID',
        'cndeliveryorder_id',
        'usr_created',
        'usr_updated',
        'usr_deleted',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $timestamps = false;
}
