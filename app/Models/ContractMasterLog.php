<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractMasterLog extends Model
{
    use SoftDeletes;
    
    public $table = 'contractmasterlog';
    protected $fillable = [
        'rcd_grp',
        'action',
        'trx_type',
        'subtrx_type',
        'contractmast_id',
        'branchid',
        'CNH_DocNo',
        'CNH_CustomerID',
        'CNH_Note',
        'CNH_PostingDate',
        'CNH_DocDate',
        'CNH_NameRef',
        'CNH_ContactRef',
        'CNH_SalesAgent1',
        'CNH_SalesAgent2',
        'CNH_TotInstPeriod',
        'CNH_Total',
        'CNH_Tax',
        'CNH_TaxableAmt',
        'CNH_NetTotal',
        'CNH_Address1',
        'CNH_Address2',
        'CNH_Address3',
        'CNH_Address4',
        'CNH_Postcode',
        'CNH_City',
        'CNH_State',
        'CNH_Country',
        'CNH_InstallAddress1',
        'CNH_InstallAddress2',
        'CNH_InstallAddress3',
        'CNH_InstallAddress4',
        'CNH_InstallPostcode',
        'CNH_InstallCity',
        'CNH_InstallState',
        'CNH_InstallCountry',
        'CNH_TNCInd',
        'CNH_CTOSInd',
        'CNH_SmsTag',
        'CNH_EmailVerify',
        'CNH_WarehouseID',
        'CNH_Status',
        'CTOS_verify',
        'CTOS_Score',
        'do_complete_ind',
        'CNH_EffectiveDay',
        'CNH_StartDate',
        'CNH_EndDate',
        'CNH_ApproveDate',
        'CNH_RejectDate',
        'CNH_RejectDesc',
        'CNH_CommissionMonth',
        'CNH_CommissionStartDate',
        'contractmastdtl_id',
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
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $timestamps = false;
}
