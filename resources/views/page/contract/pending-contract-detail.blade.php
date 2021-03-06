@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/contract/pending-contract-details.css">
@endsection

@section('content')
<div class="content">
    <div class="contract-details-panel">
        <div class="py-2">
          <a href="{{ url('/contract/pending-contract') }}" class="btn btn-secondary">
            <i class="fas fa-chevron-left"></i> Back
          </a>
        </div>
        <div>
          <a href="{{ url('/contract/pending-contract/detail/' . $contractDetails->id . '?print=1') }}" class="btn btn-primary" target="_blank">
            <i class="fas fa-print"></i> Print / <i class="fas fa-file-pdf"></i> Export
          </a>
        </div>
        <h2 class="center">
          Contract Details For | {{ $contractDetails->CNH_DocNo }}
        </h2>

        <form class="form-horizontal" method="POST" onsubmit="return validate()" action="{{ route('pending.contract.decision', $contractDetails->id) }}" enctype="multipart/form-data" >
          {{ csrf_field() }}
            <input type="hidden" name="Option" value="Approve">
            <input type="hidden" name="contract_id" value="{{ $contractDetails->id }}">
            <input type="hidden" name="customer_id" value="{{ $contractDetails->customer_id }}">
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Apply Date</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="cust_name" value="{{ $contractDetails->CNH_DocDate }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Name Of Applicant</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="cust_name" value="{{ $contractDetails->Cust_NAME }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">IC Number</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="cust_nric" value="{{ $contractDetails->Cust_NRIC }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Contact 1 Of Applicant</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="cust_phone1" value="{{ $contractDetails->telcode1 }}{{ $contractDetails->Cust_Phone1 }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Contact 2 Of Applicant</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="cust_phone2" value="{{ $contractDetails->telcode2 }}{{ $contractDetails->Cust_Phone2 }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Email Of Applicant</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="cust_email" value="{{ $contractDetails->Cust_Email }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Address</span>
                <div class="col-sm-8 input-group">
                  <textarea class="form-control" style="overflow:hidden;resize:none" name="address" readonly>
{{ $contractDetails->Cust_MainAddress1 }}, {{ ($contractDetails->Cust_MainAddress2) ? $contractDetails->Cust_MainAddress2 . ', ' : '' }}{{ $contractDetails->Cust_MainPostcode }}, {{ $city->CI_Description }}, {{ $state->ST_Description }}, {{ $country->CO_Description }}
                  </textarea>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Name Of Reference</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="cnh_name_ref" value="{{ $contractDetails->CNH_NameRef }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Contact Of Reference</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="contact_ref" value="{{ $contractDetails->CNH_ContactRef }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Product</span>
                <div class="col-sm-8 input-group">
                  <input type="hidden" class="form-control" name="product" value="{{ $itemMaster->id }}">
                  <input type="text" class="form-control" value="{{ ($itemMaster->IM_Description) ?? '' }}" readonly>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">No Of Installment Month</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="cnh_tot_inst_period" value="{{ $contractDetails->CNH_TotInstPeriod }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Unit Price</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="cnd_unit_price" value="{{ $contractDetails->CND_UnitPrice }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Seller 1</span>
                <div class="col-sm-8 input-group">
                  <input type="hidden" class="form-control" name="cnh_sales_agent1" value="{{ $contractDetails->CNH_SalesAgent1 }}" readonly>
                  <input type="text" class="form-control" value="{{ ($agent1->name) ?? '' }}" readonly>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Seller 2</span>
                <div class="col-sm-8 input-group">
                  <input type="hidden" class="form-control" name="cnh_sales_agent2" value="{{ $contractDetails->CNH_SalesAgent2 }}" readonly>
                  <input type="text" class="form-control" value="{{ ($agent2->name) ?? '' }}" readonly>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">CTOS Status</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="ctos_status" value="{{ ($contractDetails->CTOS_verify == 1) ? 'Verified' : 'Not Verified' }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">CTOS Score</span>
                <div class="col-sm-8 input-group">
                  <input type="text" class="form-control" name="ctos_score" value="{{ $contractDetails->CTOS_Score }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Effective Date</span>
                <div class="col-sm-2">
                  <select class="form-control" id="effective_day" name="effective_day" id="effective_day" required>
                    <option value="1">1</option>
                    <option value="16">16</option>  
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Start Date</span>
                <div class="col-sm-8 input-group">
                  <input type="date" name="start_date" class="form-control" id="start_date"
                  placeholder="{{ $contractDetails->start_date }}" value="{{ $contractDetails->start_date }}" onchange="updateEndDate(start_date, cnh_tot_inst_period)" required>
                  @error('start_date')
                      <div class="form-alert alert-danger">
                          <strong>{{ $message }}</strong>
                      </div>
                  @enderror 
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">End Date</span>
                <div class="col-sm-8 input-group">
                  <input type="date" name="end_date" class="form-control" id="end_date"
                  placeholder="{{ $contractDetails->end_date }}" value="{{ $contractDetails->end_date }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Commission Month</span>
                <div class="col-sm-2">
                <select class="form-control" name="commission_no_of_month">
                  <option value=""></option>
                  @for ($i = 1; $i <= $contractDetails->CNH_TotInstPeriod; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor     
                </select>
                @error('commission_no_of_month')
                  <div class="form-alert alert-danger">
                      <strong>{{ $message }}</strong>
                  </div>
                @enderror
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Commission Date</span>
                <div class="col-sm-8 input-group">
                  <input type="date" name="commission_date" class="form-control"
                  placeholder="{{ $contractDetails->start_date }}" value="{{ $contractDetails->start_date }}">
                  @error('commission_date')
                    <div class="form-alert alert-danger">
                      <strong>{{ $message }}</strong>
                    </div>
                  @enderror 
                </div>
              </div>
            </div>
            @if ($attachment->icno_file != null) 
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">IC No</span>
                <div class="col-sm-8 input-group">
                  @if($attachment->icno_mime == "application/pdf")
                    <object data="data:{{ $attachment->icno_mime }};base64,{{ $attachment->icno_file }}" type="{{ $attachment->icno_mime }}" width="100%" height="500">
                      <p>Your web browser doesn't have a PDF plugin.<br/>
                        <a onclick="openPdfOnNewTab('{{ $attachment->icno_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                      </p>
                    </object>
                  @else
                    <img src="data:{{ $attachment->icno_mime }};base64,{{ $attachment->icno_file }}" type="{{ $attachment->icno_mime }}" width="100%">
                  @endif
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Income</span>
                <div class="col-sm-8 input-group">
                  @if($attachment->income_mime == "application/pdf")
                    <object data="data:{{ $attachment->income_mime }};base64,{{ $attachment->income_file }}" type="{{ $attachment->income_mime }}" width="100%" height="500">
                      <p>Your web browser doesn't have a PDF plugin.<br/>
                        <a onclick="openPdfOnNewTab('{{ $attachment->income_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                      </p>
                    </object>
                  @else
                    <img src="data:{{ $attachment->income_mime }};base64,{{ $attachment->income_file }}" type="{{ $attachment->income_mime }}" width="100%">
                  @endif
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Bank Statement</span>
                <div class="col-sm-8 input-group">
                  @if($attachment->bankstatement_mime == "application/pdf")
                    <object data="data:{{ $attachment->bankstatement_mime }};base64,{{ $attachment->bankstatement_file }}" type="{{ $attachment->bankstatement_mime }}" width="100%" height="500">
                      <p>Your web browser doesn't have a PDF plugin. <br/>
                        <a onclick="openPdfOnNewTab('{{ $attachment->bankstatement_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                      </p>
                    </object>
                  @else
                    <img src="data:{{ $attachment->bankstatement_mime }};base64,{{ $attachment->bankstatement_file }}" type="{{ $attachment->bankstatement_mime }}" width="100%">
                  @endif
                </div>
              </div>
            </div>
            @endif
            @if ($attachment->comp_form_file != null) 
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Company Form</span>
                <div class="col-sm-8 input-group">
                  @if($attachment->comp_form_mime == "application/pdf")
                    <object data="data:{{ $attachment->comp_form_mime }};base64,{{ $attachment->comp_form_file }}" type="{{ $attachment->comp_form_mime }}" width="100%" height="500">
                      <p>Your web browser doesn't have a PDF plugin.<br/>
                        <a onclick="openPdfOnNewTab('{{ $attachment->comp_form_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                      </p>
                    </object>
                  @else
                    <img src="data:{{ $attachment->comp_form_mime }};base64,{{ $attachment->comp_form_file }}" type="{{ $attachment->comp_form_mime }}" width="100%">
                  @endif
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Company IC No</span>
                <div class="col-sm-8 input-group">
                  @if($attachment->comp_icno_mime == "application/pdf")
                    <object data="data:{{ $attachment->comp_icno_mime }};base64,{{ $attachment->comp_icno_file }}" type="{{ $attachment->comp_icno_mime }}" width="100%" height="500">
                      <p>Your web browser doesn't have a PDF plugin.<br/>
                        <a onclick="openPdfOnNewTab('{{ $attachment->comp_icno_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                      </p>
                    </object>
                  @else
                    <img src="data:{{ $attachment->comp_icno_mime }};base64,{{ $attachment->comp_icno_file }}" type="{{ $attachment->comp_icno_mime }}" width="100%">
                  @endif
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-4">Company Bank Statement</span>
                <div class="col-sm-8 input-group">
                  @if($attachment->comp_bankstatement_mime == "application/pdf")
                    <object data="data:{{ $attachment->comp_bankstatement_mime }};base64,{{ $attachment->comp_bankstatement_file }}" type="{{ $attachment->comp_bankstatement_mime }}" width="100%" height="500">
                      <p>Your web browser doesn't have a PDF plugin.<br/>
                        <a onclick="openPdfOnNewTab('{{ $attachment->comp_bankstatement_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                      </p>
                    </object>
                  @else
                    <img src="data:{{ $attachment->comp_bankstatement_mime }};base64,{{ $attachment->comp_bankstatement_file }}" type="{{ $attachment->comp_bankstatement_mime }}" width="100%">
                  @endif
                </div>
              </div>
            </div>
            @endif
            <div class="form-group row">
              <div class="input-group">
                @if($contractDetails->CTOS_verify == 0)
                  <button type="submit" class="btn btn-primary m-2 disabled" disabled>Approve</button>
                @else
                  <button type="submit" class="btn btn-primary m-2">Approve</button>
                @endif
                  <button type="button" class="btn btn-danger m-2" data-toggle="modal" data-target="#modal-reject{{ $contractDetails->id }}">Reject</button>
              </div>
            </div>
        </form>
    </div>
    <!-- Modal Reject -->
    <div id="modal-reject{{ $contractDetails->id }}" class="modal fade" role="dialog">
      <div class="modal-dialog-scrollable">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ $contractDetails->CNH_DocNo }}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" method="POST" action="{{ route('pending.contract.decision', $contractDetails->id) }}" enctype="multipart/form-data">
              {{ csrf_field() }}
            <input type="hidden" name="Option" value="Reject">
            <input type="hidden" name="contract_id" value="{{ $contractDetails->id }}">
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
</div>
@endsection
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  function updateEndDate(start_date, add_months) {
    let EndDate = document.getElementById('end_date');

    month = parseInt(add_months.value);
    date = new Date(start_date.value);

    end_date = new Date(date.setMonth(date.getMonth() + month));
    EndDate.value = end_date.toISOString().slice(0,10);
    }

  function validate() {
    let effectiveDay = document.getElementById('effective_day');
    let startDate = document.getElementById('start_date');

    date = new Date(startDate.value);
    if (date.getDate() == effectiveDay.options[effectiveDay.selectedIndex].value) {
      if (confirm("effective day is the same as start date, are you sure to proceed")) {
        
      } else {
        return false;
      }
    }
  }

  function openPdfOnNewTab(filename) {
      let base64URL = `data:application/pdf;base64, ${filename}`;
      let win = window.open();
      win.document.write('<iframe src="' + base64URL  + '" frameborder="0" style="border:0; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%;" allowfullscreen></iframe>');
    }
</script>
@endsection