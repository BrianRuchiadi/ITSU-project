<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMaster extends Model
{
    use SoftDeletes;
    
    public $table = 'customermaster';
    protected $fillable = [
        'Cust_ID',
        'Cust_AccountCode',
        'Cust_NAME',
        'Cust_RegNo',
        'Cust_GSTNo',
        'Cust_VendorID',
        'Cust_MainAddress1',
        'Cust_MainAddress2',
        'Cust_MainAddress3',
        'Cust_MainAddress4',
        'Cust_MainPostcode',
        'Cust_MainCity',
        'Cust_MainState',
        'Cust_MainCountry',
        'Cust_AltAddress1',
        'Cust_AltAddress2',
        'Cust_AltAddress3',
        'Cust_AltAddress4',
        'Cust_AltPostcode',
        'Cust_AltCity',
        'Cust_AltState',
        'Cust_AltCountry',
        'Cust_Phone1',
        'Cust_Phone2',
        'Cust_Fax1',
        'Cust_Fax2',
        'Cust_Mobile',
        'Cust_Email',
        'Cust_Skype',
        'Cust_Website',
        'Cust_ContactPerson',
        'Cust_BusinessNature',
        'Cust_CustomerCategory',
        'Cust_CATEGORY',
        'Cust_OutstandingAmount',
        'Cust_Remarks',
        'Cust_TaxExemptionNo',
        'Cust_TaxIncluded_YN',
        'Cust_Photo',
        'Cust_NRIC',
        'Cust_DOB',
        'Cust_Gender',
        'Cust_Race',
        'Cust_Religion',
        'Cust_AgingOn',
        'Cust_CreditLimit',
        'Cust_EntitlePoints_YN',
        'Cust_Points',
        'Cust_BranchPoints',
        'Cust_Salesperson',
        'Cust_Introducer',
        'Cust_ClientID',
        'Cust_SameAsBillingAdd',
        'Cust_CustomerGroup',
        'Cust_UserGeneratedAccNo_YN',
        'Cust_ExpiryDate',
        'Cust_CustomerIntroducer',
        'Cust_RegionID',
        'Cust_TaxItemized_YN',
        'Cust_Status',
        'Cust_LeadID',
        'COA_Desc',
        'PaymentTerm_Desc',
        'PaymentMode_Desc',
        'Tax_Desc',
        'Currency_Desc',
        'SalesAgent_Desc',
        'CC_Description',
        'Cust_BizSuiteClientCode',
        'Cust_SalutationID',
        'Cust_Reminder',
        'CC_ID',
        'status',
        'cust_type',
        'corp_contact_person',
        'telcode1',
        'telcode2',
        'Cust_Phone3',
        'telcode3',
        'telext1',
        'telext2',
        'telext3',
        'Cust_Email2',
        'corp_department',
        'corp_position',
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
