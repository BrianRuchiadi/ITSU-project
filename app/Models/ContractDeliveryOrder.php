<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractDeliveryOrder extends Model
{
    use SoftDeletes;
    
    public $table = 'contractdeliveryorder';
    protected $fillable = [
        'branchid',
        'CDOH_DocNo',
        'CDOH_CustomerID',
        'contractmast_id',
        'CDOH_ContractDocNo',
        'CDOH_Note',
        'CDOH_PostingDate',
        'CDOH_DocDate',
        'CDOH_Address1',
        'CDOH_Address2',
        'CDOH_Address3',
        'CDOH_Address4',
        'CDOH_Postcode',
        'CDOH_City',
        'CDOH_State',
        'CDOH_Country',
        'CDOH_InstallAddress1',
        'CDOH_InstallAddress2',
        'CDOH_InstallAddress3',
        'CDOH_InstallAddress4',
        'CDOH_InstallPostcode',
        'CDOH_InstallCity',
        'CDOH_InstallState',
        'CDOH_InstallCountry',
        'CDOH_WarehouseID',
        'CDOH_Total',
        'CDOH_TaxAmt',
        'CDOH_TaxableAmt',
        'CDOH_NetTotal',
        'CDOH_SalesAgent1',
        'CDOH_SalesAgent2',
        'pos_api_ind',
        'stock_out_ind',
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
