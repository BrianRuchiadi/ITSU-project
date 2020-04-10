@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/vendor/vendor.css">
    <link rel="stylesheet" type="text/css" href="/css/page/customer/contract-list.css">
@endsection

@section('content')
<div class="content-wrapper">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h1 class="panel-title">Contract List</h1>
      </div>
      
      @if ($user->branchind != 4)
      <form class="form-horizontal" action="{{ url('/customer/contract/search') }}" method="GET">
        {{ csrf_field() }}
        <h3 class="table-header">
          <i class="fas fa-search"></i>
          Search Contract
          <i class="fas fa-caret-down"></i>
        </h3>
        <input type="checkbox" class="search-toggler">
        <table class="table table-borderless">
          <tbody>
            <tr>
              <td>Customer</td>
              <td><input type="text" class="form-control" id="customer" name="customer"></td>
            </tr>
            <tr>
              <td>Ic No</td>
              <td><input type="text" class="form-control" id="ic_no" name="ic_no"></td>
            </tr>
            <tr>
              <td>Tel No</td>
              <td><input type="text" class="form-control" id="tel_no" name="tel_no"></td>
            </tr>
            <tr>
              <td>Contract No</td>
              <td><input type="text" class="form-control" id="contract_no" name="contract_no"></td>
            </tr>
            <tr>
              <td></td>
              <td>
                <button class="btn btn-success" type="submit">Search</button>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
      @endif

      <table class="table table-striped responsive nowrap" id="table-contract-list">
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
        </tbody>
        <tfoot>
          @if(count($contracts) == 0)
          <tr>
            <td colspan="6" class="center">No Contract Found</td>
          </tr>
          @endif
        </tfoot>
      </table>
        {{ (count($contracts) == 0) ? '' : $contracts->links() }}
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script type="text/javascript" src="/js/vendor/vendor.js"></script>
  <script type="text/javascript">
      $(document).ready( function () {
        let elTableContractList = $('#table-contract-list tbody tr')

        if (elTableContractList.length > 0) {
          let datatable = $('#table-contract-list').DataTable({
            paging : false,
            searching : false,
            responsive: true
          });
        }
      });
    @if ($error_message)
      showAlert('{{ $error_message }}', 'alert-danger');
    @endif
  </script>
@endsection