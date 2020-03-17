<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IrsItemMaster extends Model
{
    use SoftDeletes;

    public $table = 'irs_itemmaster';
    protected $fillable = [
        'IM_ID',
        'IM_Code',
        'IM_Description',
        'IM_Description2',
        'IM_Description3',
        'IM_Category',
        'IM_ItemGroup',
        'IM_Type',
        'IM_NonSaleItem_YN',
        'IM_Manufacturer',
        'IM_Brand',
        'IM_Model',
        'IM_WarrantyDays',
        'IM_Color',
        'IM_Size',
        'IM_MasterVendor',
        'IM_VendorItem',
        'IM_Discontinue_YN',
        'IM_PrintBarcodeStockIn_YN',
        'IM_BaseUOMID',
        'IM_DefaultSalesUOMID',
        'IM_DefaultPurchaseUOMID',
        'IM_ReportUOMID',
        'IM_BaseUOM',
        'IM_DefaultSalesUOM',
        'IM_DefaultPurchaseUOM',
        'IM_ReportUOM',
        'IM_Notes',
        'IM_Reminder',
        'IM_CostingCode',
        'IM_ControlAccountType',
        'IM_MatrixItemCode',
        'IM_MatrixItemColorSizeCode',
        'IM_Condiment',
        'IM_PromptSelectCondiment_YN',
        'IM_OpenPrice',
        'IM_Photo1',
        'IM_NonDiscount',
        'SalesTaxCode',
        'PurchaseTaxCode',
        'ServiceChargeTaxCode',
        'IM_PointEntitle_YN',
        'IM_PointsByAmt_YN',
        'IM_Point01',
        'IM_Point02',
        'IM_Point03',
        'IM_Point04',
        'IM_Point05',
        'IM_Point06',
        'IM_RedemptionItem_YN',
        'IM_RedemptionPoint'
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
