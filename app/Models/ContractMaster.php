<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractMaster extends Model
{
    use SoftDeletes;
    
    public $table = 'contractmaster';
    protected $fillable = [
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
        'CNH_InstallAddress1',
        'CNH_InstallAddress2',
        'CNH_InstallAddress3',
        'CNH_InstallAddress4',
        'CNH_InstallPostCode',
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
        'CNH_CommissionStartDate'
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
