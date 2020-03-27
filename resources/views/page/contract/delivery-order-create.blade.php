@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/contract/delivery-order.css">
@endsection

@section('content')

<div>
    <h1>Create Delivery Order</h1>
   <form method="POST">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">* Contract No</label>
            <div class="col-sm-8 input-group">
                <input type="text" class="form-control" name="contract_no" id="contract-no">
                <div class="input-group-append" onclick="querySearch()">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">* Date</label>
            <div class="col-sm-8 input-group">
                <input type="text" class="form-control" readonly="true">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="fas fa-calendar"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Customer Name</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" readonly="true"/>
            </div>
        </div>

        <div class="item-section">
            <h2>Item Detail</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>UOM</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Amount</th>
                        <th>Serial Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><input type="text" class="form-control"></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;">Total Amount :</td>
                        <td></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
   </form>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">
        let elContractNo = document.getElementById('contract-no');

        function querySearch() {
            console.log(['querySearch function', elContractNo.value]);
            fetch("{{ url('/api/contract/search') }}" + `?q=${elContractNo.value}`, {
                method: 'GET', // or 'PUT'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
                })
                .then((response) => {
                    return response.json();
                })
                .then((res) => {
                    console.log(['res', res]);
                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }
    </script>
@endsection