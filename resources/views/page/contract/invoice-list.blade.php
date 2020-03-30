@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/page/contract/invoice.css">
@endsection

@section('content')
<a class="btn btn-primary" href="{{url('/contract/invoices')}}">
    <i class="fas fa-chevron-left"></i>
    Back To Invoices By Date
</a>
<div class="header">
    <h1>Invoices Generated Record for : <label class="btn btn-md btn-success">{{ $selectedDate }}</label></h1>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Contract Doc No</th>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>Customer Email</th>
            <th>Billing Period</th>
            <th></th>
        </tr>
        @foreach($contractInvoices as $key => $value)
        <tr>
            <td>{{ $key + 1}}</td>
            <td>{{ $value->CSIH_ContractDocNo }}</td>
            <td>{{ $value->Cust_NAME }}</td>
            <td>{{ $value->Cust_Phone1 }}</td>
            <td>{{ $value->Cust_Email }}</td>
            <td>{{ $value->CSIH_BillingPeriod }} / {{ $value->CNH_TotInstPeriod }}</td>
            <td><a class="btn btn-sm btn-primary" href="{{ url('/contract/invoices/' . $value->id)}}">View Detail</a></td>
        </tr>
        @endforeach
    </thead>
</table>
@endsection