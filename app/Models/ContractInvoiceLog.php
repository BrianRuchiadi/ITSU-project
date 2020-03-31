<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractInvoiceLog extends Model
{
    use SoftDeletes;
    
    public $table = 'contractinvoicelog';
    protected $fillable = [
        'action',
        'trx_type',
        'subtrx_type',
        'branchid',
        'CSIH_DocNo',
        'CSIH_CustomerID',
        'contractmast_id',
        'CSIH_ContractDocNo',
        'CSIH_Note',
        'CSIH_PostingDate',
        'CSIH_DocDate',
        'CSIH_Address1',
        'CSIH_Address2',
        'CSIH_Address3',
        'CSIH_Address4',
        'CSIH_Postcode',
        'CSIH_City',
        'CSIH_State',
        'CSIH_Country',
        'CSIH_InstallAddress1',
        'CSIH_InstallAddress2',
        'CSIH_InstallAddress3',
        'CSIH_InstallAddress4',
        'CSIH_InstallPostcode',
        'CSIH_InstallCity',
        'CSIH_InstallState',
        'CSIH_InstallCountry',
        'CSIH_BillingPeriod',
        'CSIH_Total',
        'CSIH_Tax',
        'CSIH_TaxableAmt',
        'CSIH_NetTotal',
        'CSIH_BalTotal',
        'CSIH_PrevBalTotal',
        'CSIH_SalesAgent1',
        'CSIH_SalesAgent2',
        'Cancel_GLDocNo',
        'contractinvoicedtl_id',
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
        'cn_Item_Seq',
        'status',
        'usr_created'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $timestamps = false;
}
