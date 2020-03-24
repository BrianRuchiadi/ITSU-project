@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/contract/pending-contract-details.css">
@endsection

@section('content')
<div class="content">
    <div class="contract-details-panel">
        <span>
          <a href="{{ url('/pending-contract') }}" class="btn btn-primary">
            <i class="fas fa-chevron-left"></i> Back
          </a>
        </span>
        <h2 class="center">
          Contract Details For | {{ $contractDetails->CNH_DocNo }}
        </h2>

        <form class="form-horizontal">
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Product</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ ($itemMaster->IM_Description) ?? '' }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">No Of Installment Month</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ $contractDetails->CNH_TotInstPeriod }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Name Of Applicant</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ $contractDetails->Cust_NAME }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">IC Number</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ $contractDetails->Cust_NRIC }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Contact 1 Of Application</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ $contractDetails->Cust_Phone1 }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Contact 2 Of Application</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ $contractDetails->Cust_Phone2 }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Email Of Application</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ $contractDetails->Cust_Email }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Address 1</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ $contractDetails->Cust_MainAddress1 }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Address 2</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ $contractDetails->Cust_MainAddress2 }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Postcode</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ $contractDetails->Cust_MainPostcode }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">City</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ ($city->CI_Description) ?? '' }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">State</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ ($state->ST_Description) ?? '' }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Country</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ ($country->CO_Description) ?? '' }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Seller 1</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ ($agent1->name) ?? '' }}</label>  
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Seller 2</span>
                <div class="col-sm-9">
                  <label class="form-control">{{ ($agent2->name) ?? '' }}</label>  
                </div>
              </div>
            </div>
            @if ($attachment->icno_file != null) 
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">IC No</span>
                <div class="col-sm-9">
                  <object data="data:{{ $attachment->icno_mime }};base64,{{ $attachment->icno_file }}" type="{{ $attachment->icno_mime }}" width="500" height="500"></object>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Income</span>
                <div class="col-sm-9">
                  <object data="data:{{ $attachment->income_mime }};base64,{{ $attachment->income_file }}" type="{{ $attachment->income_mime }}" width="500" height="500"></object>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Bank Statement</span>
                <div class="col-sm-9">
                  <object data="data:{{ $attachment->bankstatement_mime }};base64,{{ $attachment->bankstatement_file }}" type="{{ $attachment->bankstatement_mime }}" width="500" height="500"></object>
                </div>
              </div>
            </div>
            @endif
            @if ($attachment->comp_form_file != null) 
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Company Form</span>
                <div class="col-sm-9">
                  <object data="data:{{ $attachment->comp_form_mime }};base64,{{ $attachment->comp_form_file }}" type="{{ $attachment->comp_form_mime }}" width="500" height="500"></object>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Company IC No</span>
                <div class="col-sm-9">
                  <object data="data:{{ $attachment->comp_icno_mime }};base64,{{ $attachment->comp_icno_file }}" type="{{ $attachment->comp_icno_mime }}" width="500" height="500"></object>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">Company Bank Statement</span>
                <div class="col-sm-9">
                  <object data="data:{{ $attachment->comp_bankstatement_mime }};base64,{{ $attachment->comp_bankstatement_file }}" type="{{ $attachment->comp_bankstatement_mime }}" width="500" height="500"></object>
                </div>
              </div>
            </div>
            @endif
        </form>
    </div>
    
</div>
@endsection