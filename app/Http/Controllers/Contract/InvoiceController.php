<?php
namespace App\Http\Controllers\Contract;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Auth;
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
            return redirect('/invoices');
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
        ";
        $contractInvoices = DB::select($sql);

        return view('page.contract.invoice-list', [
            'selectedDate' => $request->generated_date,
            'contractInvoices' => $contractInvoices
        ]);
    } 
}
