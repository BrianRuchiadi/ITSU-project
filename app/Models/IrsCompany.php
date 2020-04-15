<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IrsCompany extends Model
{
    use SoftDeletes;

    public $table = 'irs_company';
    protected $fillable = [
        'CO_ID',
        'CO_Code',
        'CO_Name',
        'CO_ClientID',
        'CO_Website',
        'CO_BusinessNature',
        'CO_GST_YN',
        'CO_GSTNo',
        'CO_RegistrationNo',
        'CO_ReportHeader',
        'CO_Logo',
        'CO_MainAddress1',
        'CO_MainAddress2',
        'CO_MainAddress3',
        'CO_MainAddress4',
        'CO_MainPostcode',
        'CO_MainCity',
        'CO_MainState',
        'CO_MainCountry',
        'CO_AltAddress1',
        'CO_AltAddress2',
        'CO_AltAddress3',
        'CO_AltAddress4',
        'CO_AltPostcode',
        'CO_AltCity',
        'CO_AltState',
        'CO_AltCountry',
        'CO_Phone1',
        'CO_Phone2',
        'CO_Fax1',
        'CO_Fax2',
        'CO_Email',
        'CO_ContactPerson',
        'CO_CurrencyID',
        'CO_SameAsMainAddress_YN',
        'CO_CustomerAcctNoFormat',
        'CO_DateFormat',
        'CO_Active_YN',
        'CO_DateCreated',
        'CO_CreatedBy',
        'CO_DateModified',
        'CO_ModifiedBy',
        'CO_DocumentNoFormat',
        'CO_ItemNoFormat',
        'CO_RePost_YN',
        'CO_ItemRounding',
        'CO_MatrixItemGeneratingMethod',
        'CO_AllowAdvanceStock_YN',
        'CO_UseSalesTaxFormula_YN',
        'CO_TTIDSalesTax',
        'CO_SalesTaxFormula',
        'CO_UseServiceChargeFormula_YN',
        'CO_TTIDServiceCharge',
        'CO_ServiceChargeFormula',
        'CO_UseRounding_YN',
        'CO_Round1',
        'CO_Round2',
        'CO_Round3',
        'CO_Round4',
        'CO_Round5',
        'CO_Round6',
        'CO_Round7',
        'CO_Round8',
        'CO_Round9',
        'CO_UseItemUPriceTaxFormula_YN',
        'CO_ItemUPriceTaxFormula',
        'CO_UseGstVat_YN',
        'CO_TTIDGstVat',
        'CO_UsePurchaseTax_YN',
        'CO_TTIDPurchaseTax',
        'CO_VendorRounding_YN',
        'CU_Code',
        'MainCity',
        'MainState',
        'MainCountry',
        'AltCity',
        'AltState',
        'AltCountry',
        'SalesTaxCode',
        'ServiceChargeTaxCode',
        'GstVatTaxCode',
        'PurchaseTaxCode'

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
