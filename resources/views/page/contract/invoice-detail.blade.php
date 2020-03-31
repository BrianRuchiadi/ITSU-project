@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/page/contract/invoice.css">
@endsection

@section('content')
<a class="btn btn-primary" href="{{url('/contract/invoices/list?generated_date=' . $selectedDate)}}">
    <i class="fas fa-chevron-left"></i>
    Back To Invoices List
</a>

<div class="header">
    <h1>Invoices : {{ $invoiceDetail->CSIH_DocNo }}</h1>
</div>

<table class="table">
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
</table>
@endsection