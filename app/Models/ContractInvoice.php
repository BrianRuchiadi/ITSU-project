<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractInvoice extends Model
{
    use SoftDeletes;
    
    public $table = 'contractinvoice';
    protected $fillable = [
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
