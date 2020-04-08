@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/page/contract/delivery-order.css">
@endsection

@section('content')
<a class="btn btn-secondary" href="{{url('/contract/delivery-order')}}">
    <i class="fas fa-chevron-left"></i>
    Back To Delivery Order List
</a>
<div class="py-2">
    <button type="button" class="btn btn-primary d-print-none" onclick="printPage('print-area-delivery')">
        <i class="fas fa-print"></i> Print
    </button>
</div>
<div id="print-area-delivery">
    <h1>{{ $companyAddress->CO_Name }}</h1>
    <h4>{{ $companyAddress->CO_MainAddress1 }}{{ ($companyAddress->CO_MainAddress2) ? ', ' . $companyAddress->CO_MainAddress2: '' }}{{ ($companyAddress->CO_MainAddress3) ? ', ' . $companyAddress->CO_MainAddress3: '' }}{{ ($companyAddress->CO_MainAddress4) ? ', ' . $companyAddress->CO_MainAddress4: '' }}
    </h4>
    <h4>{{ $companyAddress->CO_MainPostcode }}, {{ $companyAddress->CI_Description }}, {{ $companyAddress->ST_Description }}, {{ $companyAddress->CO_Description }}</h4>
    <h4>Tel No : {{ $companyAddress->CO_Phone1 }}</h4>
    <h4>Email : {{ $companyAddress->CO_Email }}</h4>
    <h4>Website : {{ $companyAddress->CO_Website }}</h4>

    <div class="header">
        <span class="fa-pull-right d-none d-print-block">{{ $printTime }}</span>
        <h1>Delivery Order : {{ $deliveryOrder->CDOH_DocNo }}</h1>
    </div>
    
    <table class="table" id="table-delivery">
        <tbody>
            <tr>
                <td>Document Date</td>
                <td>: {{ substr($deliveryOrder->CDOH_DocDate, 0, 10) }}</td>
            </tr>
            <tr>
                <td>Contract No</td>
                <td>: {{ $deliveryOrder->CDOH_ContractDocNo }}</td>
            </tr>
            <tr>
                <td>Customer Name</td>
                <td>: {{ $deliveryOrder->Cust_NAME }}</td>
            </tr>
            <tr>
                <td rowspan="2">Customer Address</td>
                <td>: {{ $deliveryOrder->CDOH_Address1 }}{{ ($deliveryOrder->CDOH_Address2) ? ', ' . $deliveryOrder->CDOH_Address2 : '' }}</td>
            </tr>
            <tr>
                <td>: {{ $deliveryOrder->CDOH_Postcode }}, {{ $deliveryOrder->City_Description }}, {{ $deliveryOrder->State_Description }}, {{ $deliveryOrder->Country_Description }}</td>
            </tr>
            <tr>
                <td rowspan="2">Delivery Address</td>
                <td>: {{ $deliveryOrder->CDOH_InstallAddress1 }}{{ ($deliveryOrder->CDOH_InstallAddress2) ? ', ' . $deliveryOrder->CDOH_InstallAddress2 : '' }}</td>
            </tr>
            <tr>
                <td>: {{ $deliveryOrder->CDOH_InstallPostcode }}, {{ $deliveryOrder->InstallCity_Description }}, {{ $deliveryOrder->InstallState_Description }}, {{ $deliveryOrder->InstallCountry_Description }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="item-section">
            <h2>Item Detail</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Qty</th>
                        <th>Serial Number</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $deliveryOrder->CDOD_Description }}</td>
                        <td>{{ $deliveryOrder->CDOD_Qty }}</td>
                        <td>{{ $deliveryOrder->CDOD_SerialNo }}</td>
                    </tr>
                </tbody>
            </table>
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

</script>
@endsection