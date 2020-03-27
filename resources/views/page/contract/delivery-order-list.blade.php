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

<button class="btn btn-sm btn-primary">
   <i class="fas fa-plus"></i>
   Create Delivery Order
</button>

@endsection