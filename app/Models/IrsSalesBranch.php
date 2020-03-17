<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IrsSalesBranch extends Model
{
    use SoftDeletes;

    public $table = 'irs_salesbranch';
    protected $fillable = [
        'id',
        'SB_ID',
        'SB_Code',
        'SB_Name',
        'SB_Address1',
        'SB_Address2',
        'SB_Address3',
        'SB_Address4',
        'SB_Postcode',
        'SB_City',
        'SB_State',
        'SB_Country',
        'SB_Phone1',
        'SB_Phone2',
        'SB_Fax1',
        'SB_Fax2',
        'SB_Email',
        'SB_Active_YN',
        'SB_CompanyID',
        'SB_ClientID',
        'SB_ContactPerson',
        'SB_DateCreated',
        'SB_CreatedBy',
        'SB_DateModified',
        'SB_ModifiedBy',
        'SB_PriceGroupID',
        'SB_DefaultWarehouse',
        'SB_DefaultInvoiceDocNoFormat',
        'SB_DefaultCashSalesDocNoFormat',
        'SB_DefaultCostCentre',
        'SB_DefaultProject',
        'SB_DefaultBinLocation',
        'SBPG_PGID'
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
