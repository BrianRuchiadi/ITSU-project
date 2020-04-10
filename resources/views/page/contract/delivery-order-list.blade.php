@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/vendor/vendor.css">
<link rel="stylesheet" type="text/css" href="/css/page/contract/delivery-order.css">
@endsection

@section('content')
<div class="content-wrapper">
   <div class="header">
      <h1>
         Delivery Order List
      </h1>
   </div>
   <a href="{{ url('/contract/delivery-order/create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i>Create Delivery Order
   </a>

   <table class="table table-striped responsive nowrap" id="table-delivery-order">
      <thead>
         <tr>
            <th>#</th>
            <th>Document No</th>
            <th>Document Date</th>
            <th>Contract No</th>
            <th>Status</th>
            <th>Action</th>
         </tr>
      </thead>
      <tbody>
         @foreach ($contract_delivery_orders as $key => $cdOrder)
         <tr>
            <td>{{ $contract_delivery_orders->firstItem() + $key }}</td>
            <td>{{ $cdOrder->CDOH_DocNo }}</td>
            <td>{{ substr($cdOrder->CDOH_DocDate, 0, 10) }}</td>
            <td>{{ $cdOrder->CDOH_ContractDocNo }}</td>
            <td>
               @if($cdOrder->pos_api_ind == 0)
               <label class="btn btn-sm btn-danger">Failed</label>
               @else
               <label class="btn btn-sm btn-success">Success</label>
               @endif
            </td>
            <td>
               @if($cdOrder->pos_api_ind == 0)
               <button class="btn btn-sm btn-warning" onclick="resubmitPosApi(this, {{ $cdOrder->id }})">Re - submit</button>
               @elseif ($cdOrder->pos_api_ind == 1)
               <a href="{{ route('delivery.order.detail',$cdOrder->id) }}" class="btn btn-sm btn-primary">View Details</a>
               @endif
            </td>
         </tr>
         @endforeach
      </tbody>
   </table>
   {{ $contract_delivery_orders->links() }}
</div>

@endsection

@section('scripts')
   <script type="text/javascript" src="/js/vendor/vendor.js"></script>
   <script type="text/javascript">
      $(document).ready( function () {
         let datatable = $('#table-delivery-order').DataTable({
            paging : false,
            searching : false,
            responsive: true
         });
      });

      function resubmitPosApi(element, id) {
         element.disabled = true;

         fetch('{{ url('') }}' + `/contract/api/delivery-order/${id}/resubmit`, {
            method: 'POST', // or 'PUT'
            headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            })
            .then((response) => { return response.json(); })
            .then((res) => { 
               element.disabled = false;
               if (res.status === 'success') {
                  location.reload();
               } else {
                  this.showAlert(res.errorMessage, 'alert-danger');
               }
            })
            .catch((error) => {
               element.disabled = false;
               console.log(['err', error]);
            });
      }

   </script>
@endsection