@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/contract/pending-contract-list.css">
@endsection

@section('content')
<div class="col-md-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Contract List</h3>
    </div>
    
    @if ($user->branchind == 0)
    <form class="form-horizontal" action="{{ route('contract.search') }}" method="GET">
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
              <a href="{{ url('/pending-contract') }}" class="btn btn-primary">Clear </a>
            </div>
        </div>
      </div>
    </form>
    @endif

    <table class="table table-striped">
      <thead>
        <tr>
          <th class="center">Date</th>
          <th class="center">Name</th>
          <th class="center">Contract Number</th>
          <th class="center">CTOS</th>
          <th class="center">View Details</th>
          <th class="center">Verify CTOS</th>
          <th class="center">Review Result</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($contracts as $contract)
        <tr>
            <td class="center">{{ $contract->CNH_PostingDate }}</td>
            <td class="center">{{ $contract->Cust_NAME }}</td>
            <td class="center">{{ $contract->CNH_DocNo }}</td>
            <td class="center">{{ ($contract->CTOS_verify == 1) ? 'Verified' : 'Not Verified' }}</td>     
            <td class="center"><a href="{{ route('pending.contract.detail',$contract->id) }}" class="btn btn-sm btn-primary">View Details</a></td>

            @if ($contract->CTOS_verify == 0)
            <td class="center"><button type="button" class="btn btn-sm btn-primary" onclick="verifyCTOS({{ $contract->id }})">Verify CTOS</button></td> 
            <td class="center">
              <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-approve{{ $contract->id }}" disabled>Approve</button>
              <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-reject{{ $contract->id }}" disabled>Reject</button>
            </td>
            @else
            <td class="center"><button type="button" class="btn btn-sm btn-primary" onclick="verifyCTOS({{ $contract->id }})" disabled>Verify CTOS</button></td>
            <td class="center">
              <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-approve{{ $contract->id }}">Approve</button>
              <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-reject{{ $contract->id }}">Reject</button>
            </td>
            @endif
        </tr>
        <!-- Modal Approve -->
        <div id="modal-approve{{ $contract->id }}" class="modal fade" role="dialog">
          <div class="modal-dialog-scrollable">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">{{ $contract->CNH_DocNo }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('contract.review') }}" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input type="hidden" name="Option" value="Approve">
                  <input type="hidden" name="CNH_DocNo" value="{{ $contract->CNH_DocNo }}">
                <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Contract Effective Day</span>  
                      <div class="col-sm-1">
                        <select class="form-control" id="effective_day" name="effective_day" required>
                          <option value="1">1</option>
                          <option value="16">16</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Contract Start Date</span>
                      <div class="col-sm-9">
                        <input type="date" name="start_date" class="form-control"
                        placeholder="{{ $contract->start_date }}" value="{{ $contract->start_date }}" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Contract End Date</span>
                      <div class="col-sm-9">
                        <input type="date" name="end_date" class="form-control"
                        placeholder="{{ $contract->end_date }}" value="{{ $contract->end_date }}" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Commision (No Of Months)</span>
                      <div class="col-sm-9">
                        <input class="form-control" name="commision_months" value="{{ $contract->CNH_TotInstPeriod}}">
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Commision (Disburse Effective on which month)</span>
                      <div class="col-sm-9">
                        <input type="date" name="commision_date" class="form-control"
                        placeholder="{{ $contract->start_date }}" value="{{ $contract->start_date }}" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Installation Address 1</span>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="cust_mainAddress1" value="{{ $contract->Cust_MainAddress1 }}"></input>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Installation Address 2</span>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="cust_mainAddress2" value="{{ $contract->Cust_MainAddress2 }}"></input>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Installation Post Code</span>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="cust_mainPostcode" value="{{ $contract->Cust_MainPostcode }}">
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Installation City</span>
                      <div class="col-sm-9">
                        <input type="hidden" class="form-control" name="cust_mainCity" value="{{ $contract->Cust_MainCity }}">
                        <label class="form-control">{{ $contract->CI_Description }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Installation State</span>
                      <div class="col-sm-9">
                        <input type="hidden" class="form-control" name="cust_mainState" value="{{ $contract->Cust_MainState }}">
                        <label class="form-control">{{ $contract->ST_Description }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Installation Country</span>
                      <div class="col-sm-9">
                        <input type="hidden" class="form-control" name="cust_mainCountry" value="{{ $contract->Cust_MainCountry }}">
                        <label class="form-control">{{ $contract->CO_Description }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Approve</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
        
        <!-- Modal Reject -->
        <div id="modal-reject{{ $contract->id }}" class="modal fade" role="dialog">
          <div class="modal-dialog-scrollable">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">{{ $contract->CNH_DocNo }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('contract.review') }}" enctype="multipart/form-data">
                 {{ csrf_field() }}
                <input type="hidden" name="Option" value="Reject">
                <input type="hidden" name="CNH_DocNo" value="{{ $contract->CNH_DocNo }}">
                  <div class="form-group row">
                    <div class="input-group">
                      <span class="col-sm-3">Reject Reason</span>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" name="reject_reason" required>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Reject</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
      @endforeach
      @if(count($contracts) == 0)
      <tr>
        <td colspan="7" class="center">No Contract Found</td>
      </tr>
      @endif
      
      </tbody>
    </table>
      {{ $contracts->links() }}
  </div>
</div>

@endsection 
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
        function verifyCTOS(contractId) {
                    fetch('{{ url('') }}' + `/pending-contract/verify-ctos`, {
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