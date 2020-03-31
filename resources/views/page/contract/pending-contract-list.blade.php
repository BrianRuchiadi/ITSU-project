@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/contract/pending-contract-list.css">
@endsection

@section('content')
<div class="col-md-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="panel-title">Contract List</h1>
    </div>
    
    @if ($user->branchind == 0)
    <form class="form-horizontal" action="{{ route('pending.contract.search') }}" method="GET">
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
        <div class="input-group col-4">
            <div class="input-group-prepend">
              <span class="input-group-text">
                Contract No
              </span>
            </div>
            <input type="text" class="form-control" id="contract_no" name="contract_no">
        </div>
        <div class="input-group col-3">
        </div>
        <div class="input-group col-3">
        </div>
        <div class="input-group col-2">
            <div class="input-group-prepend">
              <a href="{{ url('/contract/pending-contract') }}" class="btn btn-primary">Clear </a>
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
          <th class="center">CTOS</th>
          <th class="center">Email</th>
          <th class="center">Verify CTOS</th>
          <th class="center">Review</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($contracts as $key => $contract)
        <tr>
            <td class="center">{{ $contracts->firstItem() + $key }}</td>
            <td class="center">{{ $contract->CNH_PostingDate }}</td>
            <td class="center">{{ $contract->Cust_NAME }}</td>
            <td class="center">{{ $contract->CNH_DocNo }}</td>
            <td class="center {{ $contract->CTOS_verify == 1 ?' verified' : 'not_verified' }}">{{ ($contract->CTOS_verify == 1) ? 'Verified' : 'Not Verified' }}</td>     
            <td class="center {{ $contract->CNH_EmailVerify == 1 ?' verified' : 'not_verified' }}">
            {{ ($contract->CNH_EmailVerify == 1) ? 'Verified' : 'Not Verified' }}</td>     

            @if ($contract->CTOS_verify == 0)
            <td class="center"><button type="button" class="btn btn-sm btn-primary" onclick="verifyCTOS({{ $contract->id }})">Verify CTOS</button></td> 
            <td class="center"><a href="{{ route('pending.contract.detail',$contract->id) }}" class="btn btn-sm btn-primary disabled" disabled>View Details</a></td>
            @else
            <td class="center"><button type="button" class="btn btn-sm btn-primary disabled" onclick="verifyCTOS({{ $contract->id }})" disabled>Verify CTOS</button></td>
            <td class="center"><a href="{{ route('pending.contract.detail',$contract->id) }}" class="btn btn-sm btn-primary">View Details</a></td>
            @endif
        </tr>
      @endforeach
      @if(count($contracts) == 0)
      <tr>
        <td colspan="8" class="center">No Contract Found</td>
      </tr>
      @endif
      
      </tbody>
    </table>
      {{ $contracts->links() }}
  </div>
</div>

@endsection 
@section('scripts')
<script type="text/javascript">
      function verifyCTOS(contractId) {
          fetch('{{ url('') }}' + `/contract/pending-contract/verify-ctos`, {
              method: 'POST', // or 'PUT'
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify(
                  {
                      'id' : contractId
                  }
              )
              })
              .then((response) => { return response.json() })
              .then((res) => {
                if (res.status === 'success') {
                    location.reload();
                }
                
              })
              .catch((error) => {
                  console.log(['err', error]);
              });
        }
</script>
@endsection