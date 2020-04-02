@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/contract/delivery-order.css">
@endsection

@section('content')

<div>
    <h1 class="title">
        Create Delivery Order
        <a class="btn btn-primary" href="{{url('/contract/delivery-order')}}">
            <i class="fas fa-chevron-left"></i>
            Back To Delivery Order List
        </a>
        <button class="btn btn-danger" onclick="updateState('invalid')" id="btn-cancel">Cancel</button>
    </h1>
   <form method="POST" action="{{ url('/contract/api/delivery-order/create') }}">
        {{ csrf_field() }}
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">* Contract No</label>
            <div class="col-sm-8 input-group">
                <input type="text" class="form-control" name="contract_no" id="contract-no" required>
                <div class="input-group-append" onclick="querySearch()">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Delivery Date</label>
            <div class="col-sm-8 input-group">
                <input type="text" class="form-control" readonly="true" id="delivery-date">
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
                <input type="text" class="form-control" readonly="true" id="customer-name"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Delivery Address 1</label>
            <div class="col-sm-8">
                <textarea class="form-control" id="delivery-address-1" disabled>
                </textarea>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Delivery Address 2</label>
            <div class="col-sm-8">
                <textarea class="form-control" id="delivery-address-2" disabled>
                </textarea>
            </div>
        </div>

        <div class="item-section">
            <h2>Item Detail</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Amount</th>
                        <th>Serial Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="form-control" name="item" id="item-id"></td>
                        <td id="item-name"></td>
                        <td id="item-qty"></td>
                        <td id="item-unit-price"></td>
                        <td id="item-amount"></td>
                        <td>
                            <input type="text" class="form-control" id="item-serial-number" name="serial_no" required>
                            @error('serial_no')
                                <div class="form-alert alert-danger" style="padding-top: 5px; padding-left: 5px;">
                                    <strong>* {{ $message }}</strong>
                                </div>
                            @enderror
                        </td>
                        <td id="item-status"></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;">Total Amount :</td>
                        <td id="contract-total-amount"></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row form-group col-md-4 submit">
            <button class="btn btn-block btn-success" type="submit" id="btn-submit">Submit</button>
        </div>
   </form>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">
        let todayObject = new Date().toJSON().slice(0,10).replace(/-/g,'/');

        let elContractNo = document.getElementById('contract-no');
        let elDeliveryDate = document.getElementById('delivery-date');
        let elDeliveryAddressOne = document.getElementById('delivery-address-1');
        let elDeliveryAddressTwo = document.getElementById('delivery-address-2');
        let elCustomerName = document.getElementById('customer-name');
        let elItemId = document.getElementById('item-id');
        let elItemName = document.getElementById('item-name');
        let elItemQty = document.getElementById('item-qty');
        let elItemUnitPrice = document.getElementById('item-unit-price');
        let elItemAmount = document.getElementById('item-amount');
        let elItemStatus = document.getElementById('item-status');
        let elContractTotalAmount = document.getElementById('contract-total-amount');

        let contract = {};

        let status = 'invalid';
        let btnSubmit = document.getElementById('btn-submit');
        let btnCancel = document.getElementById('btn-cancel');

        this.updateState(status);

        function updateState(state) {
            status = state;

            if (status == 'invalid') {
                btnSubmit.disabled = true;
                elContractNo.value = '';
                this.emptyContract();
            } else if (status == 'valid') {
                btnSubmit.disabled = false;
            }
        }

        function querySearch() {
            fetch("{{ url('/contract/approved-contract/search/cnh-doc') }}" + `?contract_no=${elContractNo.value}`, {
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
                    if (res.data.length) {
                        contract = res.data[0];

                        this.showAlert('Contract is found');
                        this.populateContract();
                        this.updateState('valid');
                    } else {
                        this.showAlert('Contract is not found', 'alert-danger');
                        this.emptyContract();
                    }
                })
                .catch((error) => {
                    console.log(['err', error]);
                });
        }

        function emptyContract() {
            // elContractNo.value = contract.CNH_DocNo;
            elCustomerName.value = '';
            elDeliveryAddressOne.value = '';
            elDeliveryAddressTwo.value = '';

            elItemId.checked = false;
            elItemId.value = '';
            elItemName.innerText = '';
            elItemQty.innerText = '';
            elItemUnitPrice.innerText = '';
            elItemAmount.innerText = '';
            elItemStatus.innerText = '';
            elContractTotalAmount.innerText = '';
        }

        function populateContract() {
            elContractNo.value = contract.CNH_DocNo;
            elDeliveryDate.value = todayObject;
            elCustomerName.value = contract.Cust_Name;
            elDeliveryAddressOne.value = contract.CNH_InstallAddress1;
            elDeliveryAddressTwo.value = contract.CNH_InstallAddress2;
            elItemId.value = contract.contractmasterdtl_id;

            elItemName.innerText = contract.IM_Description;
            elItemQty.innerText = contract.CND_Qty;
            elItemUnitPrice.innerText = contract.CND_UnitPrice;
            elItemAmount.innerText = contract.CND_SubTotal;
            elItemStatus.innerText = contract.CNH_Status;
            elContractTotalAmount.innerText = contract.grand_total;
        }
    </script>
@endsection