@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/customer/contract-details.css">
@endsection

@section('content')
<div class="content">
    <div class="contract-details-panel">
        <span>
          <a href="{{ url('/contract') }}" class="btn btn-primary">
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
            <div class="form-group row">
              <div class="input-group">
                <span class="input-group-text col-sm-3">IC No</span>
                <div class="col-sm-9">
                    <img src="data:{{ $attachment->icno_mime }};base64,{{ $attachment->icno_file }}" alt="">
                </div>
              </div>
            </div>
        </form>
    </div>
    
</div>
@endsection