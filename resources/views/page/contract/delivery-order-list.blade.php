@extends('layout.dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/page/contract/delivery-order.css">
@endsection

@section('content')
<div class="header">
   <h1>
      Delivery Order List
   </h1>
</div>
<a href="{{ url('/contract/delivery-order/create') }}" class="btn btn-primary">
   <i class="fas fa-plus"></i>Create Delivery Order
</a>

<table class="table table-striped">
   <thead>
      <tr>
         <th>#</th>
         <th>Doc No</th>
         <th>Doc Date</th>
         <th>Status</th>
         <th>Action</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($contract_delivery_orders as $key => $cdOrder)
      <tr>
         <td>{{ $contract_delivery_orders->firstItem() + $key }}</td>
         <td>{{ $cdOrder->CDOH_DocNo }}</td>
         <td>{{ $cdOrder->CDOH_DocDate }}</td>
         <td>
            @if($cdOrder->pos_api_ind == 0)
            <label class="btn btn-sm btn-danger">Failed</label>
            @else
            <label class="btn btn-sm btn-success">Success</label>
            @endif
         </td>
         <td>
            @if($cdOrder->pos_api_ind == 0)
            <button class="btn btn-sm btn-primary" onclick="resubmitPosApi(this, {{ $cdOrder->id }})">Re - submit</button>
            @endif
         </td>
      </tr>
      @endforeach
   </tbody>
</table>
{{ $contract_delivery_orders->links() }}

@endsection

@section('scripts')
<script type="text/javascript">
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
               this.showAlert('{{ Session::get('showErrorMessage')}}', 'alert-danger');
            }
         })
         .catch((error) => {
            element.disabled = false;
            console.log(['err', error]);
         });
   }

</script>
@endsection