@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/print/print-contract.css">
@endsection

@section('content')
<div class="content" id="print-area-contract">
    <div class="contract-details-panel">
        <button type="button" class="btn btn-primary d-print-none" onclick="printPage('print-area-contract')">
          <i class="fas fa-print"></i> Print
        </button>
        <button type="button" class="btn btn-primary d-print-none" onclick="createPDF('export-area', {{ $contractDetails->id }})">
          <i class="fas fa-file-pdf"></i> Export To PDF
        </button>
        <div>
          <table class="table table-striped" id="export-table">
            <thead>
              <th colspan="2" class="center">
                <h2>
                  Contract Details For | {{ $contractDetails->CNH_DocNo }}
                </h2>
              </th>
            </thead>
            <tbody>
              <tr>
                <td>Apply Date</td>
                <td>{{ $contractDetails->CNH_DocDate }}</td>
              </tr>
              @if ($contractDetails->CNH_Status == 'Approve')
              <tr>
                <td>Approve Date</td>
                <td>{{ $contractDetails->CNH_ApproveDate }}</td>
              </tr>
              <tr>
                <td>Effective Day</td>
                <td>{{ $contractDetails->CNH_EffectiveDay }}</td>
              </tr>
              <tr>
                <td>Start Date</td>
                <td>{{ $contractDetails->CNH_StartDate }}</td>
              </tr>
              <tr>
                <td>End Date</td>
                <td>{{ $contractDetails->CNH_EndDate }}</td>
              </tr>
              <tr>
                <td>Commission (No of Months)</td>
                <td>{{ $contractDetails->CNH_CommissionMonth }}</td>
              </tr>
              <tr>
                <td>Commission Date</td>
                <td>{{ $contractDetails->CNH_CommissionStartDate }}</td>
              </tr>
              @endif
              @if ($contractDetails->CNH_Status == 'Reject')
              <tr>
                <td>Reject Date</td>
                <td>{{ $contractDetails->CNH_RejectDate }}</td>
              </tr>
              <tr>
                <td>Reject Reason</td>
                <td>{{ $contractDetails->CNH_RejectDesc }}</td>
              </tr>
              @endif
              <tr>
                <td>Name Of Applicant</td>
                <td>{{ $contractDetails->Cust_NAME }}</td>
              </tr>
              <tr>
                <td>IC Number</td>
                <td>{{ $contractDetails->Cust_NRIC }}</td>
              </tr>
              <tr>
                <td>Contract 1 of Applicant</td>
                <td>{{ $contractDetails->Cust_Phone1 }}</td>
              </tr>
              <tr>
                <td>Contact 2 of Applicant</td>
                <td>{{ $contractDetails->Cust_Phone2 }}</td>
              </tr>
              <tr>
                <td>Email of Applicant</td>
                <td>{{ $contractDetails->Cust_Email }}</td>
              </tr>
              <tr>
                <td>Address 1</td>
                <td>{{ $contractDetails->Cust_MainAddress1 }}</td>
              </tr>
              <tr>
                <td>Address 2</td>
                <td>{{ $contractDetails->Cust_MainAddress2 }}</td>
              </tr>
              <tr>
                <td>City</td>
                <td>{{ ($city->CI_Description) ?? '' }}</td>
              </tr>
              <tr>
                <td>State</td>
                <td>{{ ($state->ST_Description) ?? '' }}</td>
              </tr>
              <tr>
                <td>Country</td>
                <td>{{ ($country->CO_Description) ?? '' }}</td>
              </tr>
              <tr>
                <td>Product</td>
                <td>{{ ($itemMaster->IM_Description) ?? '' }}</td>
              </tr>
              <tr>
                <td>No Of Installment Month</td>
                <td>{{ $contractDetails->CNH_TotInstPeriod }}</td>
              </tr>
              <tr>
                <td>Name of Reference</td>
                <td>{{ $contractDetails->CNH_NameRef }}</td>
              </tr>
              <tr>
                <td>Contact of Reference</td>
                <td>{{ $contractDetails->CNH_ContactRef  }}</td>
              </tr>
              <tr>
                <td>Seller 1</td>
                <td>{{ ($agent1->name) ?? '' }}</td>
              </tr>
              <tr>
                <td>Seller 2</td>
                <td>{{ ($agent2->name) ?? '' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      
    </div>
</div>
@endsection
@section('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.3.1/jspdf.plugin.autotable.min.js"></script>
<script type="text/javascript">
  
  function printPage(print) {
    let printArea = document.getElementById(print).innerHTML;
    let originalContent = document.body.innerHTML;

    document.body.innerHTML = printArea;
    window.print();

    document.body.innerHTML = originalContent;    
  }

  function createPDF(print, contractId) {

    let doc = new jsPDF('p', 'pt', 'a4', true);
    doc.setFontSize(22);
    doc.autoTable({ html : '#export-table' });
    doc.save('contract-' + contractId + '.pdf');

  }
</script>
@endsection