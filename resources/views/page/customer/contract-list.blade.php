@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/customer/contract-list.css">
@endsection

@section('content')
<div class="col-md-12">
				
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Contract List</h3>
    </div>
    
    @if ($user->branchind != 4)
    <form class="form-horizontal" action="{{ route('customer.contract.search') }}" method="GET">
      {{ csrf_field() }}
      <div class="form-group row">
        <div class="input-group mb-2 col-4">
            <div class="input-group-prepend">
              <span class="input-group-text">
                Customer
              </span>
            </div>
            <input type="text" class="form-control" id="customer" name="customer">
        </div>
        <div class="input-group mb-2 col-3">
            <div class="input-group-prepend">
              <span class="input-group-text">
                IC No
              </span>
            </div>
            <input type="text" class="form-control" id="ic_no" name="ic_no">
        </div>
        <div class="input-group mb-2 col-3">
            <div class="input-group-prepend">
              <span class="input-group-text">
                Tel No
              </span>
            </div>
            <input type="text" class="form-control" id="tel_no" name="tel_no">
        </div>
        <div class="input-group mb-2 col-2">
            <div class="input-group-prepend">
              <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
      </div>
      <div class="form-group row">
        <div class="input-group mb-2 col-4">
            <div class="input-group-prepend">
              <span class="input-group-text">
                Contract No
              </span>
            </div>
            <input type="text" class="form-control" id="contract_no" name="contract_no">
        </div>
        <div class="input-group mb-2 col-3">
        </div>
        <div class="input-group mb-2 col-3">
        </div>
        <div class="input-group mb-2 col-2">
            <div class="input-group-prepend">
              <a href="{{ url('/customer/contract') }}" class="btn btn-primary">Clear </a>
            </div>
        </div>
      </div>
    </form>
    @endif

    <table class="table table-striped">
      <thead>
        <tr>
          <th class="center">No</th>
          <th class="center">Date</th>
          <th class="center">Name</th>
          <th class="center">Contract Number</th>
          <th class="center">Status</th>
          <th class="center">View Details</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($contracts as $key => $contract)
        <tr>
            <td class="center">{{ $contracts->firstItem() + $key }}</td>
            <td class="center">{{ $contract->CNH_PostingDate }}</td>
            <td class="center">{{ $contract->Cust_NAME }}</td>
            <td class="center">{{ $contract->CNH_DocNo }}</td>
            <td class="center">{{ $contract->CNH_Status }}</td>            
            <td class="center"><a href="{{ route('customer.contract.detail',$contract->id) }}" class="btn btn-sm btn-primary"> View Details</a></td>
        </tr>
      @endforeach
      @if(count($contracts) == 0)
      <tr>
        <td colspan="6" class="center">No Contract Found</td>
      </tr>
      @endif
      
      </tbody>
    </table>
      {{ (count($contracts) == 0) ? '' : $contracts->links() }}
  </div>
</div>
@endsection