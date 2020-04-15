@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/page/contract/invoice.css">
@endsection

@section('content')
@if ($selectedDate)
    <a class="btn btn-secondary" href="{{url('/contract/invoices/list?generated_date=' . $selectedDate)}}">
        <i class="fas fa-chevron-left"></i>
        Back To Invoices List
    </a>
@else 
    <a class="btn btn-secondary" href="{{url('/contract/contract-list')}}">
        <i class="fas fa-chevron-left"></i>
        Back To Contract List
    </a>
@endif

<div class="py-2">
    <button type="button" class="btn btn-primary d-print-none" onclick="printPage('print-area-invoice')">
        <i class="fas fa-print"></i> Print
    </button>
</div>
<div id="print-area-invoice">
    <h1>{{ $companyAddress->CO_Name }}</h1>
    <h4>{{ $companyAddress->CO_MainAddress1 }}{{ ($companyAddress->CO_MainAddress2) ? ', ' . $companyAddress->CO_MainAddress2: '' }}{{ ($companyAddress->CO_MainAddress3) ? ', ' . $companyAddress->CO_MainAddress3: '' }}{{ ($companyAddress->CO_MainAddress4) ? ', ' . $companyAddress->CO_MainAddress4: '' }}
    </h4>
    <h4>{{ $companyAddress->CO_MainPostcode }}, {{ $companyAddress->CI_Description }}, {{ $companyAddress->ST_Description }}, {{ $companyAddress->CO_Description }}</h4>
    <h4>Tel No : {{ $companyAddress->CO_Phone1 }}</h4>
    <h4>Email : {{ $companyAddress->CO_Email }}</h4>
    <h4>Website : {{ $companyAddress->CO_Website }}</h4>

    <div class="col-sm-6 fa-pull-left">
        <table class="table table-borderless">
            <tr>
                <td>Bill To</td>
                <td>: {{ $invoiceDetail->Cust_NAME }}</td>
            </tr>
            <tr>
                <td rowspan="2">Address</td>
                <td>: {{ $invoiceDetail->CSIH_Address1 }}{{ ($invoiceDetail->CSIH_Address2) ? ', ' . $invoiceDetail->CSIH_Address2: '' }}</td>
            </tr>
            <tr>
                <td> {{ $invoiceDetail->CSIH_Postcode }}, {{ $invoiceDetail->City_Description }}, {{ $invoiceDetail->State_Description }}, {{ $invoiceDetail->Country_Description }}</td>
            </tr>
            <tr>
                <td>Tel No</td>
                <td>: {{ $invoiceDetail->telcode1 }}{{ $invoiceDetail->Cust_Phone1 }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $invoiceDetail->Cust_Email }}</td>
            </tr>
        </table>
    </div>
    <div class="col-sm-6 fa-pull-right">
        <h2>Invoice</h2>
        <table class="table table-borderless">
            <tr>
                <td>Invoice Date</td>
                <td>: {{ $invoiceDetail->CSIH_DocDate }}</td>
            </tr>
            <tr>
                <td>Invoice No</td>
                <td>: {{ $invoiceDetail->CSIH_DocNo }}</td>
            </tr>
            <tr>
                <td>Billing Period</td>
                <td>: {{ $invoiceDetail->CSIH_BillingPeriod }} / {{ $invoiceDetail->CNH_TotInstPeriod }}</td>
            </tr>
            <tr>
                <td>Contract No</td>
                <td>: {{ $invoiceDetail->CSIH_ContractDocNo }}</td>
            </tr>
        </table>
    </div>
    
    <div class="item-section">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $invoiceDetail->CSID_Description }}</td>
                    <td>{{ $invoiceDetail->CSID_Qty }}</td>
                    <td>{{ $invoiceDetail->CSID_Total }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td>Total</td>
                    <td>{{ $invoiceDetail->CSID_Total }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  
  function printPage(print) {
    let printArea = document.getElementById(print).innerHTML;
    let originalContent = document.body.innerHTML;

    document.body.innerHTML = printArea;
    window.print();

    document.body.innerHTML = originalContent;    
  }
</script>
@endsection