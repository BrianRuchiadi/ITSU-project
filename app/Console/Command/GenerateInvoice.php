<?php

namespace App\Console\Command;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Models\CustomerMaster;
use App\Models\ContractMaster;
use App\Models\ContractMasterDtl;
use App\Models\SystemParamDetail;
use App\Models\ContractInvoice;
use App\Models\ContractInvoiceDtl;
use App\Models\ContractInvoiceLog;

use DB;

class GenerateInvoice extends Command
{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'invoice:generate';
   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Generate monthly due invoices of approved contracts';
   /**
    * Create a new command instance.
    *
    * @return void
    */
   public function __construct()
   {
       parent::__construct();
   }
   /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        $todayDateString = Carbon::now()->toDateString();
        $day = Carbon::now()->day;
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $this->info('');
        $this->info('today date is : ' . $todayDateString);

        $selectedDay = ($day < 16) ? 1 : 16;
        // any date, always force it to either 1 or 16
        $todayDateString = "{$year}-" . str_pad($month, 2, '0',STR_PAD_LEFT) . "-" . str_pad($selectedDay, 2, '0',STR_PAD_LEFT);

        $this->info('we will create for : ' . $todayDateString);
        $this->info('searching for approved contracts which effective day is ' . $selectedDay . ' ....');

        $approvedContracts = ContractMaster::where('CNH_Status', 'Approve')
            ->where('CNH_EffectiveDay', $selectedDay)
            ->where('CNH_StartDate', '<', $todayDateString)
            ->where('CNH_EndDate', '>', $todayDateString)
            ->whereNull('deleted_at')
            ->get();

        if ($approvedContracts->count() < 1) { 
            $this->info('no contract found that match the requirements');
            return; 
        }

        $this->info('found : ' . $approvedContracts->count() . ' contracts!' );
        $this->info(' ');

        $this->info('checking if the contract invoice is already generated for this month');
        $this->info('......');

        $approvedContractsIds = $approvedContracts->pluck('id');

        foreach ($approvedContractsIds as $cid) {
            $contract = ContractMaster::where('id', $cid)->first();

            $contractStartDate = Carbon::parse($contract->CNH_StartDate);
            $contractStartDay = Carbon::parse($contract->CNH_StartDate)->day;
            $contractBillingTillThisMonth = $contractStartDate->diffInMonths($todayDateString);

            // add another month to contractBillingTillThisMonth due to special condition
            if ($selectedDay == 16 && $contractStartDay < 16) {
                $contractBillingTillThisMonth+= 1;
            }

            $latestInvoice = ContractInvoice::where('contractmast_id', $cid)->orderBy('CSIH_BillingPeriod', 'desc')->first();
            // check latest invoice
            if ($latestInvoice) {
                if ($latestInvoice->CSIH_BillingPeriod == $contractBillingTillThisMonth) {
                    $this->error('This month invoice is already generated!');
                    $this->error('Command Terminated Unsuccessfully!');
                    return;
                }
            }
        }
        $this->info('please proceed with creation....');
        DB::beginTransaction();

        try {
            for ($c = 0; $c < $approvedContracts->count(); $c++) {
                $iterationNo = $c + 1;
                $this->info('START : iteration no : ' . $iterationNo);
            
                $cnsiDocSeq = SystemParamDetail::where('sysparam_cd', 'CNSIDOCSEQ')->select(['param_val'])->first();
                $cnsiDocSeqNew = $cnsiDocSeq->param_val + 1;
                $cnsiDocSeqNewFormat = str_pad((string)$cnsiDocSeqNew, 8, "0", STR_PAD_LEFT);

                SystemParamDetail::where('sysparam_cd', 'CNSIDOCSEQ')->update(['param_val' => $cnsiDocSeqNew]);

                $cnsiDocPrefix = SystemParamDetail::where('sysparam_cd', 'CNSIDOCPREFIX')->select(['param_val'])->first();
                $generatedDocNo = "{$cnsiDocPrefix->param_val}-{$cnsiDocSeqNewFormat}";

                $this->info('generated DocNo : ' . $generatedDocNo);

                // supporting query
                $customerMaster = CustomerMaster::where('id', $approvedContracts[$c]->CNH_CustomerID)->first();
                $contractMasterDtl = ContractMasterDtl::where('contractmast_id', $approvedContracts[$c]->id)->first();

                $existingContracts = ContractInvoice::where('contractmast_id' , $approvedContracts[$c]->id)
                    ->whereNull('deleted_at')
                    ->select(['CSIH_BalTotal'])
                    ->get();

                $totalContracts = $existingContracts->count() + 1;
                $totalPrevBal = ($existingContracts->count()) ?
                    array_sum(array_column($existingContracts->toArray(), 'CSIH_BalTotal')) : 0; 

                $this->info('previous contracts count :' . $totalContracts);
                $this->info('previous total balance :' . $totalPrevBal);

                $contractInvoice = ContractInvoice::create([
                    'branchid' => $approvedContracts[$c]->branchid,
                    'CSIH_DocNo' => $generatedDocNo,
                    'CSIH_CustomerID' => $approvedContracts[$c]->CNH_CustomerID,
                    'contractmast_id' => $approvedContracts[$c]->id,
                    'CSIH_ContractDocNo' => $approvedContracts[$c]->CNH_DocNo,
                    'CSIH_Note' => $approvedContracts[$c]->CNH_Note,
                    'CSIH_PostingDate' => Carbon::now(),
                    'CSIH_DocDate' => Carbon::now(),
                    'CSIH_Address1' => $customerMaster->Cust_MainAddress1,
                    'CSIH_Address2' => $customerMaster->Cust_MainAddress2,
                    'CSIH_Address3' => $customerMaster->Cust_MainAddress3,
                    'CSIH_Address4' => $customerMaster->Cust_MainAddress4,
                    'CSIH_Postcode' => $customerMaster->Cust_MainPostcode,
                    'CSIH_City' => $customerMaster->Cust_MainCity,
                    'CSIH_State' => $customerMaster->Cust_MainState,
                    'CSIH_Country' => $customerMaster->Cust_MainCountry,
                    'CSIH_InstallAddress1' => $approvedContracts[$c]->CNH_InstallAddress1,
                    'CSIH_InstallAddress2' => $approvedContracts[$c]->CNH_InstallAddress2,
                    'CSIH_InstallAddress3' => $approvedContracts[$c]->CNH_InstallAddress3,
                    'CSIH_InstallAddress4' => $approvedContracts[$c]->CNH_InstallAddress4,
                    'CSIH_InstallPostcode' => $approvedContracts[$c]->CNH_InstallPostcode,
                    'CSIH_InstallCity' => $approvedContracts[$c]->CNH_InstallCity,
                    'CSIH_InstallState' => $approvedContracts[$c]->CNH_InstallState,
                    'CSIH_InstallCountry' => $approvedContracts[$c]->CNH_InstallCountry,
                    'CSIH_BillingPeriod' => $totalContracts,
                    'CSIH_Total' => $approvedContracts[$c]->CNH_Total,
                    'CSIH_Tax' => $approvedContracts[$c]->CNH_Tax,
                    'CSIH_TaxableAmt' => $approvedContracts[$c]->CNH_TaxableAmt,
                    'CSIH_NetTotal' => $approvedContracts[$c]->CNH_NetTotal,
                    'CSIH_BalTotal' => $approvedContracts[$c]->CNH_NetTotal,
                    'CSIH_PrevBalTotal' => $totalPrevBal,
                    'CSIH_SalesAgent1' => $approvedContracts[$c]->CNH_SalesAgent1,
                    'CSIH_SalesAgent2' => $approvedContracts[$c]->CNH_SalesAgent2,
                    'usr_created' => null
                ]);
                $this->info('contractinvoice generated!');

                $contractInvoiceDtl = ContractInvoiceDtl::create([
                    'contractinvoice_id' => $contractInvoice->id,
                    'CSID_ItemID' => $contractMasterDtl->CND_ItemID,
                    'CSID_Description' => $contractMasterDtl->CND_Description,
                    'CSID_ItemUOMID' => $contractMasterDtl->CND_ItemUOMID,
                    'CSID_ItemTypeID' => $contractMasterDtl->CND_ItemTypeID,
                    'CSID_Qty' => $contractMasterDtl->CND_Qty,
                    'CSID_UnitPrice' => $contractMasterDtl->CND_UnitPrice,
                    'CSID_SubTotal' => $contractMasterDtl->CND_SubTotal,
                    'CSID_TaxAmt' => $contractMasterDtl->CND_TaxAmt,
                    'CSID_TaxableAmt' => $contractMasterDtl->CND_TaxableAmt,
                    'CSID_Total' => $contractMasterDtl->CND_Total,
                    'CSID_SerialNo' => $contractMasterDtl->CND_SerialNo,
                    'CSID_ItemSeq' => $contractMasterDtl->CND_ItemSeq,
                    'cn_item_Seq' => $contractMasterDtl->CND_ItemSeq
                ]);

                $contractInvoiceLog = ContractInvoiceLog::create([
                    'action' => 'ADD',
                    'trx_type' => 'SI',
                    'subtrx_type' => '',
                    'branchid' => $contractInvoice->branchid,
                    'CSIH_DocNo' => $contractInvoice->CSIH_DocNo,
                    'CSIH_CustomerID' => $contractInvoice->CSIH_CustomerID,
                    'contractmast_id' => $contractInvoice->contractmast_id,
                    'CSIH_ContractDocNo' => $contractInvoice->CSIH_ContractDocNo,
                    'CSIH_Note' => $contractInvoice->CSIH_Note,
                    'CSIH_PostingDate' => $contractInvoice->CSIH_PostingDate,
                    'CSIH_DocDate' => $contractInvoice->CSIH_DocDate,
                    'CSIH_Address1' => $contractInvoice->CSIH_Address1,
                    'CSIH_Address2' => $contractInvoice->CSIH_Address2,
                    'CSIH_Address3' => $contractInvoice->CSIH_Address3,
                    'CSIH_Address4' => $contractInvoice->CSIH_Address4,
                    'CSIH_Postcode' => $contractInvoice->CSIH_Postcode,
                    'CSIH_City' => $contractInvoice->CSIH_City,
                    'CSIH_State' => $contractInvoice->CSIH_State,
                    'CSIH_Country' => $contractInvoice->CSIH_Country,
                    'CSIH_InstallAddress1' => $contractInvoice->CSIH_InstallAddress1,
                    'CSIH_InstallAddress2' => $contractInvoice->CSIH_InstallAddress2,
                    'CSIH_InstallAddress3' => $contractInvoice->CSIH_InstallAddress3,
                    'CSIH_InstallAddress4' => $contractInvoice->CSIH_InstallAddress4,
                    'CSIH_InstallPostcode' => $contractInvoice->CSIH_InstallPostcode,
                    'CSIH_InstallCity' => $contractInvoice->CSIH_InstallCity,
                    'CSIH_InstallState' => $contractInvoice->CSIH_InstallState,
                    'CSIH_InstallCountry' => $contractInvoice->CSIH_InstallCountry,
                    'CSIH_BillingPeriod' => $contractInvoice->CSIH_BillingPeriod,
                    'CSIH_Total' => $contractInvoice->CSIH_Total,
                    'CSIH_Tax' => $contractInvoice->CSIH_Tax,
                    'CSIH_TaxableAmt' => $contractInvoice->CSIH_TaxableAmt,
                    'CSIH_NetTotal' => $contractInvoice->CSIH_NetTotal,
                    'CSIH_BalTotal' => $contractInvoice->CSIH_BalTotal,
                    'CSIH_PrevBalTotal' => $contractInvoice->CSIH_PrevBalTotal,
                    'CSIH_SalesAgent1' => $contractInvoice->CSIH_SalesAgent1,
                    'CSIH_SalesAgent2' => $contractInvoice->CSIH_SalesAgent2,
                    'contractinvoicedtl_id' => $contractInvoiceDtl->id,
                    'CSID_ItemID' => $contractInvoiceDtl->CSID_ItemID,
                    'CSID_Description' => $contractInvoiceDtl->CSID_Description,
                    'CSID_ItemUOMID' => $contractInvoiceDtl->CSID_ItemUOMID,
                    'CSID_ItemTypeID' => $contractInvoiceDtl->CSID_ItemTypeID,
                    'CSID_Qty' => $contractInvoiceDtl->CSID_Qty,
                    'CSID_UnitPrice' => $contractInvoiceDtl->CSID_UnitPrice,
                    'CSID_SubTotal' => $contractInvoiceDtl->CSID_SubTotal,
                    'CSID_TaxAmt' => $contractInvoiceDtl->CSID_TaxAmt,
                    'CSID_TaxableAmt' => $contractInvoiceDtl->CSID_TaxableAmt,
                    'CSID_Total' => $contractInvoiceDtl->CSID_Total,
                    'CSID_SerialNo' => $contractInvoiceDtl->CSID_SerialNo,
                    'CSID_ItemSeq' => $contractInvoiceDtl->CSID_ItemSeq,
                    'cn_Item_Seq' => $contractInvoiceDtl->cn_Item_Seq,
                    'status' => '',
                    'usr_created' => null
                ]);

                $this->info('END : iteration no : ' . $iterationNo);
                $this->info(' ');
            }
            
            DB::commit();
            $this->info('Command Finished Successfully!');
        } catch (Exception $e) {
            DB::rollback();
            $this->error('Command Terminated Unsuccessfully!');
            return $e->getMessage();
        }

    }
}