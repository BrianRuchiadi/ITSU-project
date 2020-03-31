<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractInvoiceDtl extends Model
{
    use SoftDeletes;
    
    public $table = 'contractinvoicedtl';
    protected $fillable = [
        'contractinvoice_id',
        'CSID_ItemID',
        'CSID_Description',
        'CSID_ItemUOMID',
        'CSID_ItemTypeID',
        'CSID_Qty',
        'CSID_UnitPrice',
        'CSID_SubTotal',
        'CSID_TaxAmt',
        'CSID_TaxableAmt',
        'CSID_Total',
        'CSID_SerialNo',
        'CSID_ItemSeq',
        'cn_item_Seq',
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
