@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/page/contract/invoice.css">
@endsection

@section('content')
<a class="btn btn-secondary" href="{{url('/contract/invoices/list?generated_date=' . $selectedDate)}}">
    <i class="fas fa-chevron-left"></i>
    Back To Invoices List
</a>
<div class="py-2">
    <button type="button" class="btn btn-primary d-print-none" onclick="printPage('print-area-invoice')">
        <i class="fas fa-print"></i> Print
    </button>
    <button type="button" class="btn btn-primary d-print-none" onclick="createPDF('header', {{ $invoiceDetail->id }})">
        <i class="fas fa-file-pdf"></i> Export To PDF
    </button>
</div>
<div id="print-area-invoice">
    <div class="header d-print-none">
        <h1>Invoices : {{ $invoiceDetail->CSIH_DocNo }}</h1>
    </div>

    <table class="table" id="table-invoice">
        <thead class="d-none d-print-block">
            <tr>
                <td colspan="2">Invoices : {{ $invoiceDetail->CSIH_DocNo }}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Invoice Payment Period</td>
                <td>: {{ $invoiceDetail->CSIH_BillingPeriod }} / {{ $invoiceDetail->CNH_TotInstPeriod }}</td>
            </tr>
            <tr>
                <td>Invoice No</td>
                <td>: {{ $invoiceDetail->CSIH_DocNo }}</td>
            </tr>
            <tr>
                <td>Customer Name</td>
                <td>: {{ $invoiceDetail->Cust_NAME }}</td>
            </tr>
            <tr>
                <td>Customer Email</td>
                <td>: {{ $invoiceDetail->Cust_Email }}</td>
            </tr>
            <tr>
                <td>Customer Phone</td>
                <td>: {{ $invoiceDetail->Cust_Phone1 }}</td>
            </tr>
            <tr>
                <td>Install Address 1</td>
                <td>:
                    {{ $invoiceDetail->CSIH_InstallAddress1 }}
                </td>
            </tr>
            @if ($invoiceDetail->CSIH_InstallAddress2)
                <tr>
                    <td>Install Address 2 </td>
                    <td>:
                        {{ $invoiceDetail->CSIH_InstallAddress2 }}
                    </td>
                </tr>
            @endif
            <tr>
                <td>City</td>
                <td>: {{ $invoiceDetail->City_Description }}</td>
            </tr>
            <tr>
                <td>State</td>
                <td>: {{ $invoiceDetail->State_Description }}</td>
            </tr>
            <tr>
                <td>Country</td>
                <td>: {{ $invoiceDetail->Country_Description }}</td>
            </tr>
            <tr>
                <td>Net Total</td>
                <td>: {{ $invoiceDetail->CSIH_NetTotal }}</td>
            </tr>
            <tr>
                <td>Balance Total</td>
                <td>: {{ $invoiceDetail->CSIH_BalTotal }}</td>
            </tr>
            <tr>
                <td>Prev Balance Total</td>
                <td>: {{ $invoiceDetail->CSIH_PrevBalTotal }}</td>
            </tr>
        </tbody>
    </table>
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

  function createPDF(print, invoice) {

    let doc = new jsPDF('p', 'pt', 'a4', true);
    doc.setFontSize(22);
    doc.autoTable({ html : '#table-invoice' });
    doc.save('invoice-' + invoice + '.pdf');

  }
</script>
@endsection