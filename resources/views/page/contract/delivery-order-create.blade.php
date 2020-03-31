@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/contract/delivery-order.css">
@endsection

@section('content')

<div>
    <a class="btn btn-secondary" href="{{url('/contract/delivery-order')}}">
        <i class="fas fa-chevron-left"></i>
        Back To Delivery Order List
    </a>
    <h1 class="title">
        Create Delivery Order
        
        <button class="btn btn-danger" onclick="updateState('invalid')" id="btn-cancel">Clear</button>
    </h1>
   <form method="POST" action="{{ url('/contract/api/delivery-order/create') }}">
        {{ csrf_field() }}
        <div class="form-group row">
            <label class="col-sm-4 col-form-label required">Contract No</label>
            <div class="col-sm-8 input-group">
                <input type="text" class="form-control" name="contract_no" id="contract-no" required>
                <div class="input-group-append" onclick="openSearchModal()">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label required">Delivery Date</label>
            <div class="col-sm-8 input-group">
                <input type="date" class="form-control" name="delivery_date" id="delivery-date" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Customer Name</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" readonly="true" id="customer-name"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Customer Address</label>
            <div class="col-sm-8">
                <textarea class="form-control" id="customer-address" disabled>
                </textarea>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label required">Delivery Address 1</label>
            <div class="col-sm-8">
                <textarea class="form-control" id="delivery-address-1" name="delivery_address_1" required>
                </textarea>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Delivery Address 2</label>
            <div class="col-sm-8">
                <textarea class="form-control" id="delivery-address-2" name="delivery_address_2">
                </textarea>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label required">Delivery Postcode</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="delivery-postcode" name="delivery_postcode" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label required">Delivery Country</label>
            <div class="col-sm-8">
                <select class="form-control" id="delivery-country" name="delivery_country" onchange="populateStates(this)" required>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label required">Delivery State</label>
            <div class="col-sm-8">
                <select class="form-control" id="delivery-state" name="delivery_state" onchange="populateCities(this)" required>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label required">Delivery City</label>
            <div class="col-sm-8">
                <select class="form-control" id="delivery-city" name="delivery_city" required>
                </select>
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

<div class="modal-master" id="search-modal" onclick="closeSearchModal()">
    <div class="modal-wrapper" onclick="stopBubbling(event)">
        <h1><i class="fas fa-search"></i> Contract Search</h1>
        <table>
            <tr>
                <td>
        </table>
    </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">
        let todayObject = new Date().toJSON().slice(0,10).replace(/-/g,'/');

        let elContractNo = document.getElementById('contract-no');
        let elDeliveryDate = document.getElementById('delivery-date');
        let elCustomerAddress = document.getElementById('customer-address');
        let elDeliveryAddressOne = document.getElementById('delivery-address-1');
        let elDeliveryAddressTwo = document.getElementById('delivery-address-2');
        let elDeliveryPostcode = document.getElementById('delivery-postcode');
        let elDeliveryCountry = document.getElementById('delivery-country');
        let elDeliveryState = document.getElementById('delivery-state');
        let elDeliveryCity = document.getElementById('delivery-city');
        let elCustomerName = document.getElementById('customer-name');
        let elItemId = document.getElementById('item-id');
        let elItemName = document.getElementById('item-name');
        let elItemQty = document.getElementById('item-qty');
        let elItemUnitPrice = document.getElementById('item-unit-price');
        let elItemAmount = document.getElementById('item-amount');
        let elItemStatus = document.getElementById('item-status');
        let elContractTotalAmount = document.getElementById('contract-total-amount');

        let elSearchModal = document.getElementById('search-modal');

        let contract = {};

        let status = 'invalid';
        let btnSubmit = document.getElementById('btn-submit');
        let btnCancel = document.getElementById('btn-cancel');

        this.updateState(status);
        this.getCountryOptions();

        function stopBubbling(ev) {
            ev.stopPropagation();
        }

        function openSearchModal() {
            elSearchModal.classList.add('open');
        }

        function closeSearchModal() {
            elSearchModal.classList.remove('open');
        }

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
            elCustomerAddress.value = '';
            elDeliveryAddressOne.value = '';
            elDeliveryAddressTwo.value = '';
            elDeliveryPostcode.value = '';
            elDeliveryCountry.value = '';
            elDeliveryState.value = '';
            elDeliveryCity.value = '';

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
            elCustomerAddress.value = `${contract.CNH_InstallAddress1},${contract.CNH_InstallAddress2},${contract.CNH_InstallPostcode},${contract.CI_Description},${contract.ST_Description},${contract.CO_Description}`;
            elItemId.value = contract.contractmasterdtl_id;

            elItemName.innerText = contract.IM_Description;
            elItemQty.innerText = contract.CND_Qty;
            elItemUnitPrice.innerText = contract.CND_UnitPrice;
            elItemAmount.innerText = contract.CND_SubTotal;
            elItemStatus.innerText = contract.CNH_Status;
            elContractTotalAmount.innerText = contract.grand_total;
        }

        function removeOptions(option) {
            while (option.hasChildNodes()) {
                option.removeChild(option.firstChild);
            }
        }

        function populateStates(option) {
            // text : option.options[option.selectedIndex].innerHTML,
            // value : option.value
            fetch('{{ url('') }}' + `/customer/api/country/states?co_id=` + option.value, {
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
                    // stateOptions
                    this.removeOptions(elDeliveryCity);
                    this.removeOptions(elDeliveryState);

                    let option = document.createElement('option');
                    option.setAttribute('value', '');
                    option.appendChild(document.createTextNode('-- Select State --'));

                    elDeliveryState.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.ST_ID);
                        option.appendChild(document.createTextNode(each.ST_Description));

                        elDeliveryState.appendChild(option);
                    }
                    
                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        elDeliveryState.value = '{{ session()->get('state') }}';
                        this.populateCities(elDeliveryState);
                    @endif

                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateCities(option) {
            fetch('{{ url('') }}' + `/customer/api/state/cities?st_id=` + option.value , {
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
                    // cityOptions
                    this.removeOptions(elDeliveryCity);

                    let option = document.createElement('option');
                    option.setAttribute('value', '');
                    option.appendChild(document.createTextNode('-- Select City --'));

                    elDeliveryCity.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.CI_ID);
                        option.appendChild(document.createTextNode(each.CI_Description));

                        elDeliveryCity.appendChild(option);
                    }
                    
                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        elDeliveryCity.value = '{{ session()->get('city') }}';
                    @endif

                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function getCountryOptions() {
            fetch("{{ url('/customer/api/countries') }}", {
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
                    // countryOptions
                    this.clearItems(elDeliveryCountry);
                    let option = document.createElement('option');
                    option.setAttribute('value', '');
                    option.appendChild(document.createTextNode('-- Select Country --'));

                    elDeliveryCountry.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.CO_ID);
                        option.appendChild(document.createTextNode(each.CO_Description));

                        elDeliveryCountry.appendChild(option);
                    }

                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        elDeliveryCountry.value = '{{ session()->get('country') }}';
                        this.populateStates(elDeliveryCountry);
                    @endif
                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function clearItems(item)
        {
            for (i = item.options.length-1; i >= 0; i--) {
                item.options[i] = null;
            }
        }
    </script>
@endsection