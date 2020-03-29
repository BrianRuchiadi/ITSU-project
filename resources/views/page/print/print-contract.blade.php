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
      <h2 class="center">
        Contract Details For | {{ $contractDetails->CNH_DocNo }}
      </h2>
      <table class="table table-striped">
        <tr>
          <td>Product</td>
          <td>{{ ($itemMaster->IM_Description) ?? '' }}</td>
        </tr>
        <tr>
          <td>No Of Installment Month</td>
          <td>{{ $contractDetails->CNH_TotInstPeriod }}</td>
        </tr>
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
        @if ($status != 'Pending') 
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
      </table>
      
    </div>
</div>
@endsection
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js'></script>
<script type="text/javascript">
  
  function printPage(print) {
    let printArea = document.getElementById(print).innerHTML;
    let originalContent = document.body.innerHTML;
    console.log(printArea);
    document.body.innerHTML = printArea;
    window.print();

    document.body.innerHTML = originalContent;    
  }
</script>
@endsection