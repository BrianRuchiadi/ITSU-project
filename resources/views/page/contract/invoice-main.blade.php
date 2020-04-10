@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/vendor/vendor.css">
<link rel="stylesheet" type="text/css" href="/css/page/contract/invoice.css">
@endsection

@section('content')

<div class="content-wrapper">
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


    <table class="table table-striped limited responsive nowrap" id="table-invoice-list">
        <thead>
            <tr>
                <th>#</th>
                <th>Contract Generated Date</th>
                <th>Total Invoices</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contractInvoicesDate as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $value->CSIH_PostingDate }}</td>
                <td>{{ $value->total_contract }}</td>
                <td>
                    <a class="btn btn-primary btn-sm" href="{{ url('/contract/invoices/list?generated_date=' . $value->CSIH_PostingDate) }}">View Invoices</a>
                </td> 
            </tr>   
            @endforeach
        </tbody>
    </table>
    {{ $contractInvoicesDate->links() }}
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/vendor/vendor.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {
            let datatable = $('#table-invoice-list').DataTable({
                paging : false,
                searching : false,
                responsive: true
            });
        });
        @if (Session::has('showErrorMessage'))
            showAlert('{{ Session::get('showErrorMessage')}}', 'alert-danger');
        @endif
    </script>
@endsection