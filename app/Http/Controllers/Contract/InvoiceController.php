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
        $sql = "
            SELECT 
                COUNT(`id`) As 'total_contract',
                `CSIH_PostingDate`
            FROM `contractinvoice`
            WHERE 1
            GROUP BY CSIH_PostingDate
        ";

        $contractInvoicesDate = DB::select($sql);    

        return view('page.contract.invoice-main', [
            'todayDate' => $todayDate,
            'contractInvoicesDate' => $contractInvoicesDate
        ]);
    }

    public function showInvoicesListByDate(Request $request) {
        if (!$request->generated_date) {
            return redirect('/contract/invoices');
        }
        $sql = "
            SELECT
                cont_inv.`id`,
                cont_inv.`CSIH_BillingPeriod`,
                cont_inv.`CSIH_ContractDocNo`,
                cont_mas.`CNH_TotInstPeriod`,
                cust_mas.`Cust_NAME`,
                cust_mas.`Cust_Phone1`,
                cust_mas.`Cust_Email`
            FROM
                `contractinvoice` cont_inv,
                `contractmaster` cont_mas,
                `customermaster` cust_mas
            WHERE 1
                AND cont_inv.`CSIH_ContractDocNo` = cont_mas.`CNH_DocNo`
                AND cont_mas.`CNH_CustomerID` = cust_mas.`id`
                AND cont_inv.`CSIH_PostingDate` = '{$request->generated_date}'
        ";
        $contractInvoices = DB::select($sql);

        return view('page.contract.invoice-list', [
            'selectedDate' => $request->generated_date,
            'contractInvoices' => $contractInvoices
        ]);
    } 

    public function showInvoiceDetail(Request $request, ContractInvoice $invoice) {
        $sql = "
            SELECT
                cont_inv.`CSIH_DocNo`,
                cont_inv.`CSIH_BillingPeriod`,
                cont_inv.`CSIH_InstallAddress1`,
                cont_inv.`CSIH_InstallAddress2`,
                cont_inv.`CSIH_InstallPostcode`,
                cont_inv.`CSIH_InstallCity`,
                cont_inv.`CSIH_InstallState`,
                cont_inv.`CSIH_InstallCountry`,
                cont_inv.`CSIH_NetTotal`,
                cont_inv.`CSIH_BalTotal`,
                cont_inv.`CSIH_PrevBalTotal`,
                cont_inv.`CSIH_SalesAgent1`,
                cont_inv.`CSIH_SalesAgent2`,
                cont_mas.`CNH_TotInstPeriod`,
                cust_mas.`Cust_NAME`,
                cust_mas.`Cust_Phone1`,
                cust_mas.`Cust_Email`,
                cust_mas.`Cust_NRIC`,
                irs_city.`CI_Description` AS 'City_Description',
                irs_state.`ST_Description` AS 'State_Description',
                irs_country.`CO_Description` AS 'Country_Description'
            FROM
                `contractinvoice` cont_inv,
                `contractmaster` cont_mas,
                `customermaster` cust_mas,
                `irs_state` irs_state,
                `irs_city` irs_city,
                `irs_country` irs_country
            WHERE 1
                AND cont_inv.`id` = {$invoice->id}
                AND cont_mas.`CNH_CustomerID` = cust_mas.`id`
                AND cont_inv.`CSIH_ContractDocNo` = cont_mas.`CNH_DocNo`
                AND cont_inv.`CSIH_InstallCity` = irs_city.`id`
                AND cont_inv.`CSIH_InstallState` = irs_state.`id`
                AND cont_inv.`CSIH_InstallCountry` = irs_country.`id`
        ";

        $invoiceDetail = DB::select($sql)[0];
        // dd($invoiceDetail);
        return view('page.contract.invoice-detail', [
            'selectedDate' => $request->generated_date,
            'invoiceDetail' => $invoiceDetail
        ]);
    }
}
