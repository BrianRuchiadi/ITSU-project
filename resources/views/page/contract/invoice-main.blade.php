@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/page/contract/invoice.css">
@endsection

@section('content')

<div class="header">
    <h1>Invoices Generated Record</h1>

    @if (config('app.env') == 'local')
    <div class="danger-section">
        <form method="POST" action="{{ url('/contract/api/invoice/generate') }}">
            {{ csrf_field() }}
            <strong>Dangerous Function! Only show during UAT</strong>
            <br/>
            <input type="hidden" name="secret_pass" value="{{ config('app.secret_dev_pass')}}"/>
            <button class="btn btn-warning" type="submit">Generate Invoice</button>
            @error('secret_pass')
                <div class="form-alert alert-danger">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
            @if (Session::has('showCommandOutput'))
                <div class="form-alert alert-primary">
                    {{ Session::get('showCommandOutput') }}
                </div>
            @endif
        </form>
    </div>
    @endif

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

@section('scripts')
    <script type="text/javascript">
        @if (Session::has('showErrorMessage'))
            console.log(['a']);
            showAlert('{{ Session::get('showErrorMessage')}}', 'alert-danger');
        @endif
    </script>
@endsection