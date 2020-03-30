@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/page/contract/invoice.css">
@endsection

@section('content')

<div class="header">
    <h1>Invoices Generated Record</h1>
    <h5 style="font-style:italic">Today Date : {{ $todayDate }} </h5>
</div>


<table class="table table-striped limited">
    <thead>
        <tr>
            <th>#</th>
            <th>Contract Generated Date</th>
            <th>Total Invoices</th>
            <th></th>
        </tr>

        @foreach ($contractInvoicesDate as $key => $value)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $value->CSIH_PostingDate }}</td>
            <td>{{ $value->total_contract }}</td>
            <td>
                <a class="btn btn-primary" href="{{ url('/contract/invoices/list?generated_date=' . $value->CSIH_PostingDate) }}">View Invoices</a>
            </td> 
        </tr>   
        @endforeach
    </thead>
</table>
@endsection