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

      <div class="table-wrapper contract">
        <h3>Contract List</h3>
        <i class="fas fa-caret-down"></i>
        <input type="checkbox" class="contract-toggler">
        <table class="table table-striped responsive nowrap" id="table-contract-list">
          <thead>
            <tr>
              <th class="center">No</th>
              <th class="center">Date</th>
              <th class="center">Name</th>
              <th class="center">Contract Number</th>
              <th class="center">Status</th>
              <th class="center">View Details</th>
              <th class="center">Action</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($contracts as $key => $contract)
            <tr onclick="displaySingleViewInvoice({{ $contract->id }})">
                <td class="center">{{ $contracts->firstItem() + $key }}</td>
                <td class="center">{{ $contract->CNH_PostingDate }}</td>
                <td class="center">{{ $contract->Cust_NAME }}</td>
                <td class="center">{{ $contract->CNH_DocNo }}</td>
                <td class="center">{{ $contract->CNH_Status }}</td>            
                <td class="center"><a href="{{ route('customer.contract.detail', $contract->id) }}" class="btn btn-sm btn-primary" onclick="event.stopPropagation()"> View Details</a></td>
                <td class="center">
                  @if ($contract->CNH_Status == 'Reject')
                  <a href="{{ route('customer.contract.resubmit', $contract->id)}}" class="btn btn-sm btn-warning" onclick="event.stopPropagation()">Resubmit</a>
                  @endif
                </td>
            </tr>
          @endforeach      
          </tbody>
          <tfoot>
            @if (!count($contracts))
              <tr>
                <td colspan="7" class="center">No Contract Found</td>
              </tr>
            @endif
          </tfoot>
        </table>
        @if (count($contracts))
          {{ (count($contracts->items()) == 0) ? '' : $contracts->links() }}
        @endif
      </div>

      <div class="table-wrapper hide" id="table-wrapper-invoice">
        <h3 id="invoice-list-title">Invoice List</h3>
        <i class="fas fa-caret-down"></i>
        <input type="checkbox" class="invoice-toggler">
        <table class="table table-striped responsive nowrap" id="table-invoice-list">
          <thead>
            <tr>
              <th>#</th>
              <th>Invoice No</th>
              <th>Invoice Date</th>
              <th>Billing Period</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

      <div class="table-wrapper hide" id="table-wrapper-delivery">
        <h3 id="delivery-list-title">Delivery Order List</h3>
        <i class="fas fa-caret-down"></i>
        <input type="checkbox" class="delivery-toggler">
        <table class="table table-striped responsive nowrap" id="table-delivery-list">
          <thead>
            <tr>
              <th>#</th>
              <th>Delivery Order No</th>
              <th>Document Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script type="text/javascript" src="/js/vendor/vendor.js"></script>
  <script type="text/javascript">
    @if (count($invoices) > 0)
      let invoices = {!! $invoices !!};
    @else
      let invoices = [];
    @endif

    @if (count($contracts) > 0)
      let contracts = {!! json_encode($contracts->items()) !!};
    @else
      let contracts = [];
    @endif 

    @if (count($delivery_orders) > 0)
      let deliveryOrders = {!! $delivery_orders !!};
    @else
      let deliveryOrders = [];
    @endif

    let elTableDeliveryList = document.getElementById('table-delivery-list');
    let elDeliveryListTitle = document.getElementById('delivery-list-title');
    let elTableWrapperDelivery = document.getElementById('table-wrapper-delivery');

    let elTableInvoiceList = document.getElementById('table-invoice-list');
    let elInvoiceListTitle = document.getElementById('invoice-list-title');
    let elTableWrapperInvoice = document.getElementById('table-wrapper-invoice');

    let datatableInvoiceList = null;
    let datatableDeliveryList = null;

    $(document).ready( function () {
      let elTableContractList = $('#table-contract-list tbody tr')

      if (elTableContractList.length > 0) {
        let datatableContractList = $('#table-contract-list').DataTable({
          paging : false,
          searching : false,
          responsive: true
        });
      }
    });
    @if ($error_message)
      showAlert('{{ $error_message }}', 'alert-danger');
    @endif

    function displaySingleViewInvoice(contractId) {
        let relevantInvoices = invoices.filter(inv => inv.contractmast_id === contractId);
        let relevantDeliveryOrders = deliveryOrders.filter(del => del.contractmast_id === contractId);
        let selectedContract = contracts.find(c => c.id === contractId);
        let message = ''

        elInvoiceListTitle.innerHTML = `Invoices For :  ${selectedContract.CNH_DocNo} (${relevantInvoices.length} records)`;
        elDeliveryListTitle.innerHTML = `Delivery Order For :  ${selectedContract.CNH_DocNo} (${relevantDeliveryOrders.length} records)`;

        clearInvoices();
        clearDelivery();

        if (relevantInvoices.length && selectedContract.CNH_Status == 'Approve') {
          message += "Invoices found! Please scroll down to view! <br/>";
          elTableWrapperInvoice.classList.remove('hide');

          for (let i = 0; i < relevantInvoices.length; i++) {
            let newRow = elTableInvoiceList.getElementsByTagName('tbody')[0].insertRow(i);

            let colRow_Index = newRow.insertCell(0);
            colRow_Index.innerHTML = i + 1;

            let colRow_InvoiceNo = newRow.insertCell(1);
            colRow_InvoiceNo.innerHTML = relevantInvoices[i].CSIH_DocNo;

            let colRow_InvoiceDate = newRow.insertCell(2);
            colRow_InvoiceDate.innerHTML = relevantInvoices[i].CSIH_DocDate.substring(0, 10);

            let colRow_BillingPeriod = newRow.insertCell(3);
            colRow_BillingPeriod.innerHTML = relevantInvoices[i].CSIH_BillingPeriod + ' / ' + selectedContract.CNH_TotInstPeriod; 

            let colRow_ActionButton = newRow.insertCell(4);
            colRow_ActionButton.innerHTML = '<a href="/customer/invoice/' +  relevantInvoices[i].id +  '" class="btn btn-primary btn-sm">View Detail</a>';
          }

          datatableInvoiceList = $('#table-invoice-list').DataTable({
              paging : false,
              searching : false,
              responsive: true,
          });
        } else {
          message += "No invoice found! <br/>";
          elTableWrapperInvoice.classList.add('hide');
          insertNotFoundRowInvoice();
        }

        if (relevantDeliveryOrders.length && selectedContract.CNH_Status == 'Approve') {
          message += "Delivery orders found! Please scroll down to view! <br/>"
          elTableWrapperDelivery.classList.remove('hide');

          for (let i = 0; i < relevantDeliveryOrders.length; i++) {
            let newRow = elTableDeliveryList.getElementsByTagName('tbody')[0].insertRow(i);

            let colRow_Index = newRow.insertCell(0);
            colRow_Index.innerHTML = i + 1;

            let colRow_DeliveryOrderNo = newRow.insertCell(1);
            colRow_DeliveryOrderNo.innerHTML = relevantDeliveryOrders[i].CDOH_DocNo;

            let colRow_DocDate = newRow.insertCell(2);
            colRow_DocDate.innerHTML = relevantDeliveryOrders[i].CDOH_DocDate.substring(0, 10);

            let colRow_Status = newRow.insertCell(3);
            colRow_Status.innerHTML = (relevantDeliveryOrders[i].pos_api_ind == 1) ?
              '<label class="btn btn-success btn-sm">Success</label>' :
              '<label class="btn btn-danger btn-sm">Failed</label>'
            ; 

            let colRow_ActionButton = newRow.insertCell(4);
            colRow_ActionButton.innerHTML = '<a href="/customer/delivery-order/' +  relevantDeliveryOrders[i].id +  '" class="btn btn-primary btn-sm">View Detail</a>';
          }

          datatableDeliveryList = $('#table-delivery-list').DataTable({
              paging : false,
              searching : false,
              responsive: true,
          });
        } else {
          message += "No delivery orders found! <br/>";
          elTableWrapperDelivery.classList.add('hide');
          insertNotFoundRowDelivery();
        }

        showAlert(message, 'alert-dark');
    }

    function clearInvoices() {
      let length = elTableInvoiceList.rows.length;

      if (length < 2) { return; }
      for (let i = 1; i < length; i++) {
          elTableInvoiceList.deleteRow(1);
      }

      if ($.fn.DataTable.isDataTable('#table-invoice-list')) {
          $('#table-invoice-list').DataTable().clear().destroy();
      }
    }

    function clearDelivery() {
      let length = elTableDeliveryList.rows.length;

      if (length < 2) { return; }
      for (let i = 1; i < length; i++) {
          elTableDeliveryList.deleteRow(1);
      }

      if ($.fn.DataTable.isDataTable('#table-delivery-list')) {
          $('#table-delivery-list').DataTable().clear().destroy();
      }
    }

    function insertNotFoundRowInvoice() {
      let newRow = elTableInvoiceList.getElementsByTagName('tbody')[0].insertRow(0);

      let colRowLabelAction = newRow.insertCell(0);
      colRowLabelAction.colSpan = 5;
      colRowLabelAction.innerHTML = 'No Record Found!';
    }

    function insertNotFoundRowDelivery() {
      let newRow = elTableDeliveryList.getElementsByTagName('tbody')[0].insertRow(0);

      let colRowLabelAction = newRow.insertCell(0);
      colRowLabelAction.colSpan = 5;
      colRowLabelAction.innerHTML = 'No Record Found!';
    }

  </script>
@endsection