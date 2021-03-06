<?php
namespace App\Http\Controllers\Contract;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use DB;

use App\Models\ContractInvoice;

class InvoiceController extends Controller
{
    public function showInvoicesByGeneratedDate(Request $request) {
        $todayDate = Carbon::now()->toDateString();

        $contractInvoicesDate = DB::table('contractinvoice')
                                ->select(DB::raw('count(*) as total_contract, CSIH_PostingDate'))
                                ->groupBy('CSIH_PostingDate')
                                ->paginate(30);

        return view('page.contract.invoice-main', [
            'todayDate' => $todayDate,
            'contractInvoicesDate' => $contractInvoicesDate
        ]);
    }

    public function showInvoicesListByDate(Request $request) {
        if (!$request->generated_date) {
            return redirect('/contract/invoices');
        }
        
        $contractInvoices = DB::table('contractinvoice')
                            ->join('contractmaster', 'contractinvoice.CSIH_ContractDocNo', '=', 'contractmaster.CNH_DocNo')
                            ->join('customermaster', 'contractmaster.CNH_CustomerID', '=', 'customermaster.id')
                            ->where('contractinvoice.CSIH_PostingDate', '=', $request->generated_date)
                            ->select([
                                'contractinvoice.id',
                                'contractinvoice.CSIH_BillingPeriod',
                                'contractinvoice.CSIH_ContractDocNo',
                                'contractmaster.CNH_TotInstPeriod',
                                'customermaster.Cust_NAME',
                                'customermaster.Cust_Phone1',
                                'customermaster.Cust_Email'
                            ])->paginate(30);

        return view('page.contract.invoice-list', [
            'selectedDate' => $request->generated_date,
            'contractInvoices' => $contractInvoices
        ]);
    } 

    public function showInvoiceDetail(Request $request, ContractInvoice $invoice) {
        $sql = "
            SELECT
                cont_inv.`id`,
                cont_inv.`CSIH_DocDate`,
                cont_inv.`CSIH_DocNo`,
                cont_inv.`CSIH_ContractDocNo`,
                cont_inv.`CSIH_BillingPeriod`,
                cont_inv.`CSIH_Address1`,
                cont_inv.`CSIH_Address2`,
                cont_inv.`CSIH_Postcode`,
                cont_inv.`CSIH_City`,
                cont_inv.`CSIH_State`,
                cont_inv.`CSIH_Country`,
                cont_inv.`CSIH_NetTotal`,
                cont_inv.`CSIH_BalTotal`,
                cont_inv.`CSIH_PrevBalTotal`,
                cont_inv.`CSIH_SalesAgent1`,
                cont_inv.`CSIH_SalesAgent2`,
                cont_inv_dtl.`CSID_Description`,
                cont_inv_dtl.`CSID_Qty`,
                cont_inv_dtl.`CSID_Total`,
                cont_mas.`CNH_TotInstPeriod`,
                cust_mas.`Cust_NAME`,
                cust_mas.`Cust_Phone1`,
                cust_mas.`Cust_Email`,
                cust_mas.`Cust_NRIC`,
                cust_mas.`telcode1`,
                irs_city.`CI_Description` AS 'City_Description',
                irs_state.`ST_Description` AS 'State_Description',
                irs_country.`CO_Description` AS 'Country_Description'
            FROM
                `contractinvoice` cont_inv,
                `contractinvoicedtl` cont_inv_dtl,
                `contractmaster` cont_mas,
                `customermaster` cust_mas,
                `irs_state` irs_state,
                `irs_city` irs_city,
                `irs_country` irs_country
            WHERE 1
                AND cont_inv.`id` = {$invoice->id}
                AND cont_inv.`id` = cont_inv_dtl.`contractinvoice_id`
                AND cont_mas.`CNH_CustomerID` = cust_mas.`id`
                AND cont_inv.`CSIH_ContractDocNo` = cont_mas.`CNH_DocNo`
                AND cont_inv.`CSIH_City` = irs_city.`CI_ID`
                AND cont_inv.`CSIH_State` = irs_state.`ST_ID`
                AND cont_inv.`CSIH_Country` = irs_country.`CO_ID`
        ";

        $invoiceDetail = DB::select($sql)[0];

        $companyAddress = DB::table('irs_company')
        ->join('irs_city', 'irs_company.CO_MainCity', '=', 'irs_city.CI_ID')
        ->join('irs_state', 'irs_company.CO_MainState', '=', 'irs_state.ST_ID')
        ->join('irs_country', 'irs_company.CO_MainCountry', '=', 'irs_country.CO_ID')
        ->where('irs_company.CO_Code', '=', env('BRANCH_ID'))
        ->select([
            'irs_company.id',
            'irs_company.CO_Name',
            'irs_company.CO_MainAddress1',
            'irs_company.CO_MainAddress2',
            'irs_company.CO_MainAddress3',
            'irs_company.CO_MainAddress4',
            'irs_company.CO_MainPostcode',
            'irs_company.CO_MainCity',
            'irs_company.CO_MainState',
            'irs_company.CO_MainCountry',
            'irs_company.CO_Phone1',
            'irs_company.CO_Email',
            'irs_company.CO_Website',
            'irs_city.CI_Description',
            'irs_state.ST_Description',
            'irs_country.CO_Description'
        ])->first();

        $invoiceDetail->CSIH_DocDate = Carbon::parse($invoiceDetail->CSIH_DocDate)->format('d/m/Y');
        
        return view('page.contract.invoice-detail', [
            'selectedDate' => $request->generated_date,
            'invoiceDetail' => $invoiceDetail,
            'companyAddress' => $companyAddress
        ]);
    }
}
