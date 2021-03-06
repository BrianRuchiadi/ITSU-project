@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/customer/application-form.css">
    <link rel="stylesheet" type="text/css" href="/css/vendor/vendor.css">
@endsection

@section('content')
    <h2 class="center">Application Form</h2>
    <form method="POST" action="{{ url('/customer/api/resubmit', $contractDetails->id) }}" enctype="multipart/form-data" id="form">
        {{ csrf_field() }}
        @if (!Session::has('displaySMSTag'))
            <h3 class="section-header" onclick="toggleRequirements('reject-information')">
                1. Reject Information
                <i class="fas fa-caret-down right"></i>
            </h3>
            <section class="group reject-information">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Reject Reason</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Reject Reason" id="reject-reason" name="reject_reason" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Reject Date</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Reject Date" id="reject-date" name="reject_date" readonly>
                    </div>
                </div>
            </section>
            
            <h3 class="section-header" onclick="toggleRequirements('personal-information')">
                2. Personal Information
                <i class="fas fa-caret-down right"></i>
            </h3>
            <section class="group personal-information">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">Name Of Applicant</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Name Of Applicant" id="name-of-applicant" name="name_of_applicant" required>
                        @error('name_of_applicant')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">IC Number</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="IC Number" id="ic-number" name="ic_number" required>
                        @error('ic_number')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">Contact 1 Of Applicant<br/>
                        <strong>(Will be used for SMS Tag) </strong>
                    </label>
                    <div class="col-sm-8">
                        <select class="form-control col-sm-3 d-inline select2-contact" id="tel-code-options-1" name="tel_code_1" required></select>
                        @error('tel_code_1')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                        <input type="text" class="form-control col-sm-8 d-inline" placeholder="123456789" id="contact-one-of-applicant" name="contact_one_of_applicant" required>
                        @error('contact_one_of_applicant')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Contact 2 Of Applicant</label>
                    <div class="col-sm-8">
                        <select class="form-control col-sm-3 d-inline select2-contact" id="tel-code-options-2" name="tel_code_2"></select>
                        @error('tel_code_2')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                        <input type="text" class="form-control col-sm-8 d-inline" placeholder="123456789" id="contact-two-of-applicant" name="contact_two_of_applicant">
                        @error('contact_two_of_applicant')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">Email Of Applicant</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" placeholder="jane.doe@gmail.com" id="email-of-applicant" name="email_of_applicant" required>
                        @error('email_of_applicant')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">Address 1</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" placeholder="Address 1" id="address-one" name="address_one" required></textarea>
                        @error('address_one')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Address 2</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" placeholder="Address 2" id="address-two" name="address_two"></textarea>
                        @error('address_two')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">Postcode</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Postcode" id="postcode" name="postcode" required>
                        @error('postcode')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">Country</label>
                    <div class="col-sm-8">
                        <select class="form-control js-example-basic-single" id="country-options" onchange="populateStates(this, 'change')" name="country" required>
                        </select>
                        @error('country')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">State</label>
                    <div class="col-sm-8">
                        <select class="form-control js-example-basic-single" id="state-options" onchange="populateCities(this, 'change')" name="state" required>
                        </select>
                        @error('state')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">City</label>
                    <div class="col-sm-8">
                        <select class="form-control js-example-basic-single" id="city-options" name="city" required>
                        </select>
                        @error('city')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Name Of Reference</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Name Of Reference" id="name-of-reference" name="name_of_reference">
                        @error('name_of_reference')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Contact of Reference</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Contact Of Reference" id="contact-of-reference" name="contact_of_reference">
                        @error('contact_of_reference')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                </div>
            </section>
            <h3 class="section-header" onclick="toggleRequirements('product-installment')">
                3. Product Installment
                <i class="fas fa-caret-down right"></i>
            </h3>
            <section class="group product-installment">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">Product</label>
                    <div class="col-sm-8">
                        <select class="js-example-basic-single form-control" id="item-options" name="product" onchange="populateMonthOptions(this, 'change')" required></select>
                        @error('product')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label required">No Of Installment Month</label>
                    <div class="col-sm-8">
                        <select class="form-control js-example-basic-single" id="month-options" name="no_of_installment_month" onchange="populateUnitPrice(product, this, 'change')" required></select>   
                        @error('no_of_installment_month')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Unit Price</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="unit-price" name="unit_price" readonly required>
                    </div>
                </div>
                <div class="form-group row" style="margin-bottom: 0px;">
                    <div class="col-sm-4">
                        <label class="col-form-label">Applicant Type</label>
                    </div>
                    <div class="col-sm-8">
                    
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="applicant_type" value="individual_applicant"  id="radio-individual-applicant"
                                    onclick="changeApplicantType('individual_applicant')" required>
                                Individual Applicant
                            </label>
                        </div>

                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="applicant_type" value="self_employed" id="radio-self-employed"
                                    onclick="changeApplicantType('self_employed')">
                                    Self Employed
                            </label>
                        </div>
                        @error('applicant_type')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                </div>
            </section>

            <input type="hidden" name="file_inclusion" value="include">
            <h3 class="section-header" onclick="toggleRequirements('referral-information')">
                4. Referral Information
                <i class="fas fa-caret-down right"></i>
            </h3>
            <section class="group referral-information">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Seller 1</label>
                    <div class="col-sm-8">
                        <select class="form-control js-example-basic-single" id="seller-one" name="seller_one" required></select>
                        @error('seller_one')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Seller 2</label>
                    <div class="col-sm-8">
                        <select class="form-control js-example-basic-single" id="seller-two" name="seller_two"></select>
                        @error('seller_two')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                </div>
            </section>

            <div id="individual-applicant-requirement">
                <h3 class="important-note" onclick="toggleRequirements('employed-requirement')">
                    Individual Applicant
                    <i class="fas fa-caret-down"></i>
                </h3>
                <div class="form-group row employed-requirement">
                    <label class="col-sm-6 col-form-label">One Photocopy Of I/C</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" id="file-individual-icno" name="file_individual_icno" accept="image/*, application/pdf">
                        @error('file_individual_icno')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>

                <div class="form-group row employed-requirement">
                    <label class="col-sm-6 col-form-label">Photocopy Of 3 Months Proof Of Income</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" id="file-individual-income" name="file_individual_income" accept="image/*, application/pdf">
                        @error('file_individual_income')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>

                <div class="form-group row employed-requirement">
                    <label class="col-sm-6 col-form-label">Updated Bank Statement Or Savings Passbook</label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" id="file-individual-bankstatement" name="file_individual_bankstatement" accept="image/*, application/pdf">
                        @error('file_individual_bankstatement')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>
            </div>

            <div id="self-employed-requirement">
                <h3 class="important-note" onclick="toggleRequirements('self-employed-requirement')">
                    Self-Employed
                    <i class="fas fa-caret-down"></i>
                </h3>

                <div class="form-group row self-employed-requirement">
                    <label class="col-sm-6 col-form-label">
                        Form J and Business Registration Form<br/>
                        (Borang A and D)
                    </label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" id="file-company-form" name="file_company_form" accept="image/*, application/pdf">
                        @error('file_company_form')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>

                <div class="form-group row self-employed-requirement">
                    <label class="col-sm-6 col-form-label">
                        Photocopied I/C Of Proprietor / Partners / Directors
                    </label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" id="file-company-icno" name="file_company_icno" accept="image/*, application/pdf">
                        @error('file_company_icno')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>

                <div class="form-group row self-employed-requirement">
                    <label class="col-sm-6 col-form-label">
                        Updated 3 Month Bank Statement
                    </label>
                    <div class="col-sm-6">
                        <input type="file" class="form-control" id="file-company-bankstatement" name="file_company_bankstatement" accept="image/*, application/pdf">
                        @error('file_company_bankstatement')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>
            </div>
            <input type="hidden" name="previous_applicant_type" value="{{ $attachment->type }}">
            @if ($attachment->icno_file != null)
            <h3 class="important-note" onclick="toggleRequirements('previous-individual-applicant')">
                    Previous Individual Applicant Uploaded File
                    <i class="fas fa-caret-down"></i>
                </h3>
            <div class="form-group row previous-individual-applicant" id="previous-individual-applicant">
              <div class="input-group col-sm-4" style="text-align:center">
                <span class="col-sm-12 m-2">One Photocopy Of I/C</span>
                  @if($attachment->icno_mime == "application/pdf")
                    <object class="m-auto" data="data:{{ $attachment->icno_mime }};base64,{{ $attachment->icno_file }}" type="{{ $attachment->icno_mime }}" width="300" height="300">
                        <p>Your web browser doesn't have a PDF plugin.<br/>
                        <a onclick="openPdfOnNewTab('{{ $attachment->icno_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                      </p>
                    </object>
                  @else
                    <img class="m-auto" src="data:{{ $attachment->icno_mime }};base64,{{ $attachment->icno_file }}" type="{{ $attachment->icno_mime }}" width="100%">
                  @endif
              </div>
              <div class="input-group col-sm-4" style="text-align:center">
                <span class="col-sm-12 m-2">Photocopy Of 3 Months Proof Of Income</span>
                  @if($attachment->income_mime == "application/pdf")
                    <object class="m-auto" data="data:{{ $attachment->income_mime }};base64,{{ $attachment->income_file }}" type="{{ $attachment->income_mime }}" width="300" height="300">
                        <p>Your web browser doesn't have a PDF plugin.<br/>
                            <a onclick="openPdfOnNewTab('{{ $attachment->income_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                        </p>
                    </object>
                  @else
                    <img class="m-auto" src="data:{{ $attachment->income_mime }};base64,{{ $attachment->income_file }}" type="{{ $attachment->income_mime }}" width="100%">
                  @endif
              </div>
              <div class="input-group col-sm-4" style="text-align:center">
                <span class="col-sm-12 m-2">Updated Bank Statement Or Savings Passbook</span>
                  @if($attachment->bankstatement_mime == "application/pdf")
                    <object class="m-auto" data="data:{{ $attachment->bankstatement_mime }};base64,{{ $attachment->bankstatement_file }}" type="{{ $attachment->bankstatement_mime }}" width="300" height="300">
                        <p>Your web browser doesn't have a PDF plugin. <br/>
                            <a onclick="openPdfOnNewTab('{{ $attachment->bankstatement_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                        </p>
                    </object>
                  @else
                    <img class="m-auto" src="data:{{ $attachment->bankstatement_mime }};base64,{{ $attachment->bankstatement_file }}" type="{{ $attachment->bankstatement_mime }}" width="100%">
                  @endif
              </div>
            </div>
            @endif

            @if ($attachment->comp_form_file != null) 
            <h3 class="important-note" onclick="toggleRequirements('previous-self-employed')">
                Previous Self Employed Uploaded File
                <i class="fas fa-caret-down"></i>
            </h3>
            <div class="form-group row previous-self-employed" id="previous-self-employed">
              <div class="input-group col-sm-4" style="text-align:center">
                <span class="col-sm-12 m-2">Form J and Business Registration Form<br/>
                 (Borang A and D)</span>
                  @if($attachment->comp_form_mime == "application/pdf")
                    <object class="m-auto" data="data:{{ $attachment->comp_form_mime }};base64,{{ $attachment->comp_form_file }}" type="{{ $attachment->comp_form_mime }}" width="300" height="300">
                        <p>Your web browser doesn't have a PDF plugin.<br/>
                            <a onclick="openPdfOnNewTab('{{ $attachment->comp_form_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                        </p>
                    </object>
                  @else
                    <img class="m-auto" src="data:{{ $attachment->comp_form_mime }};base64,{{ $attachment->comp_form_file }}" type="{{ $attachment->comp_form_mime }}" width="100%">
                  @endif
              </div>
              <div class="input-group col-sm-4" style="text-align:center">
                <span class="col-sm-12 m-2">Photocopied I/C Of Proprietor / Partners / Directors</span>
                  @if($attachment->comp_icno_mime == "application/pdf")
                    <object class="m-auto" data="data:{{ $attachment->comp_icno_mime }};base64,{{ $attachment->comp_icno_file }}" type="{{ $attachment->comp_icno_mime }}" width="300" height="300">
                        <p>Your web browser doesn't have a PDF plugin.<br/>
                            <a onclick="openPdfOnNewTab('{{ $attachment->comp_icno_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                        </p>
                    </object>
                  @else
                    <img class="m-auto" src="data:{{ $attachment->comp_icno_mime }};base64,{{ $attachment->comp_icno_file }}" type="{{ $attachment->comp_icno_mime }}" width="100%">
                  @endif
              </div>
              <div class="input-group col-sm-4" style="text-align:center">
                <span class="col-sm-12 m-2">Updated 3 Month Bank Statement</span>
                @if($attachment->comp_bankstatement_mime == "application/pdf")
                    <object class="m-auto" data="data:{{ $attachment->comp_bankstatement_mime }};base64,{{ $attachment->comp_bankstatement_file }}" type="{{ $attachment->comp_bankstatement_mime }}" width="300" height="300">
                        <p>Your web browser doesn't have a PDF plugin.<br/>
                            <a onclick="openPdfOnNewTab('{{ $attachment->comp_bankstatement_file}}')" style="text-decoration: underline">Click to display on new tab</a>
                        </p>
                    </object>
                @else
                    <img class="m-auto" src="data:{{ $attachment->comp_bankstatement_mime }};base64,{{ $attachment->comp_bankstatement_file }}" type="{{ $attachment->comp_bankstatement_mime }}" width="100%">
                @endif
              </div>
            </div>
            @endif
            <div class="notes">
                <div id="individual-applicant-notes">
                    <h3 class="important-note" onclick="toggleRequirements('employed')">
                        Applicant's Requirements
                        <i class="fas fa-caret-down"></i>
                    </h3>
                    <span class="employed">* Individual Malaysian citizen aged 18 years and above</span>
                    <span class="employed">* Applicants should be employed for at least 6 months in current employment</span>
                    <span class="employed">* Minimum monthly net pay : RM 800.00</span>
                    <span class="employed">* Office telephone number and HP / home telephone number is compulsory</span>
                    <span class="employed">* One contactable referee</span>
                    <span class="employed">* Applicant must be contactable</span>
                </div>

                <div id="self-employed-notes">
                    <h3 class="important-note" onclick="toggleRequirements('self-employed')">
                        Self-Employed Malaysian citizen aged 18 years and above
                        <i class="fas fa-caret-down"></i>
                    </h3>
                    <span class="self-employed">* Self-employed applicants current employment must be at least 1 year</span>
                    <span class="self-employed">* Minimum monthly net pay : RM 1,000.00</span>
                    <span class="self-employed">* Providing office telephone and HP / home telephone number is compulsory</span>
                    <span class="self-employed">* One contactable referee</span>
                    <span class="self-employed">* Applicant must be contactable</span>
                </div>
            </div>

            <div class="form-group row" style="margin-top: 10px;">
                <div class="col-sm-9">
                    <div>
                        <input type="checkbox" name="tandcitsu" id="tandcitsu" value="1" required>
                        <label for="tandc">I have read & agree to the <a href="https://www.google.com" target="blank" class="tandc">Terms & Condition</a></label>

                        @error('tandcitsu')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                    <div>
                        <input type="checkbox" name="tandcctos" id="tandcctos" value="1" required>
                        <label for="tandc">I have read & agree with <a href="https://www.google.com" target="blank" class="tandc">CTOS Consent Authorization</a></label>

                        @error('tandcctos')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror 
                    </div>
                    
                </div>
                <div class="col-sm-3" style="margin: auto;">
                    <button type="submit" class="btn btn-block btn-primary" onclick="unhideRequirement()">Submit</button>
                </div>
            </div>        
        @endif
            @if (Session::has('displaySMSTag'))
                <h3 class="section-header" onclick="toggleRequirements('verification-information')">
                    Verification Information
                    <button class="btn btn-warning" id="sms-status-button" style="cursor: not-allowed" disabled>Status : </button>
                    <i class="fas fa-caret-down right"></i>
                </h3>
                <section class="group verification-information">
                    <div class="form-group row">
                        <button type="button" id="sms-tag-send-button" class="btn btn-block btn-success" onclick="clickSendSmsTag()">Send SMS</button>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Contact 1 Of Applicant<br/>
                            <strong>(Please Include Country Code) </strong>
                        </label>
                        <div class="col-sm-8">
                        <select class="form-control col-sm-3 d-inline select2-contact" id="tel-code-options-1" name="tel_code_1"></select>
                        @error('tel_code_1')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                        <input type="text" class="form-control col-sm-8 d-inline" placeholder="123456789" id="contact-one-of-applicant" name="contact_one_of_applicant" required>
                        @error('contact_one_of_applicant')
                            <div class="form-alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Contact 1 SMS tag</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="SMS tag" name="contact_one_sms_tag" id="contact-one-sms-tag">
                        </div>
                    </div>

                    <div class="form-group row">
                        <button type="button" class="btn btn-block btn-success" onclick="verifySmsTag()" id="sms-tag-verify-button">Verify</button>
                    </div>
                </section>

                <div class="form-group row last">
                    <button type="button" id="sms-tag-submit-button" class="btn btn-primary btn-block" onclick="reSubmitFinalForm({{ $contractDetails->id }})" disabled>Submit</button>
                </div>
            @endif
    </form>
@endsection

@section('scripts')
<script type="text/javascript" src="/js/vendor/vendor.js"></script>

    <script type="text/javascript">     
        $(document).ready(function() {
            $(".js-example-basic-single").select2({
                width: '100%'
            });
            $(".select2-contact").select2({
                height: '100px'
            });
        });
        // PAGE LOAD

            let form = document.getElementById('form');

            // START : reject information
            let rejectReason = document.getElementById('reject-reason');
            let rejectDate = document.getElementById('reject-date');
            // END : reject information

            // START : personal information
            let nameOfApplicant = document.getElementById('name-of-applicant');
            let icNumber = document.getElementById('ic-number');
            let contactOneOfApplicant = document.getElementById('contact-one-of-applicant');
            let contactTwoOfApplicant = document.getElementById('contact-two-of-applicant');
            let emailOfApplicant = document.getElementById('email-of-applicant');
            let addressOne = document.getElementById('address-one');
            let telCodeOptions1 = document.getElementById('tel-code-options-1');
            let addressTwo = document.getElementById('address-two');
            let telCodeOptions2 = document.getElementById('tel-code-options-2');
            let postcode = document.getElementById('postcode');
            let countryOptions = document.getElementById('country-options');
            let stateOptions = document.getElementById('state-options');
            let cityOptions = document.getElementById('city-options');
            let nameOfReference = document.getElementById('name-of-reference');
            let contactOfReference = document.getElementById('contact-of-reference');
            let radioSelfEmployed = document.getElementById('radio-self-employed');
            let radioIndividualApplicant = document.getElementById('radio-individual-applicant');
            // END : personal information

            // START : product installment 
            let itemOptions = document.getElementById('item-options');
            let monthOptions = document.getElementById('month-options');
            let unitPrice = document.getElementById('unit-price');
            // END : product installment

            // START : referral information
            let sellerOne = document.getElementById('seller-one');
            let sellerTwo = document.getElementById('seller-two');
            // END : referral information

            // START : File and requirements note\
            let individualApplicantRequirement = document.getElementById('individual-applicant-requirement');
            let selfEmployedRequirement = document.getElementById('self-employed-requirement');
            let previousIndividualApplicant = document.getElementById('previous-individual-applicant');
            let previousSelfEmployed = document.getElementById('previous-self-employed');

            let individualApplicantIC = document.getElementById('file-individual-icno');
            let individualApplicantIncome = document.getElementById('file-individual-income');
            let individualApplicantBankStatement = document.getElementById('file-individual-bankstatement');

            let selfEmployedForm = document.getElementById('file-company-form');
            let selfEmployedIC = document.getElementById('file-company-icno');
            let selfEmployedBankStatement = document.getElementById('file-company-bankstatement');

            let individualApplicantNotes = document.getElementById('individual-applicant-notes');
            let selfEmployedNotes = document.getElementById('self-employed-notes');

            var contractDetails = {!! json_encode($contractDetails, JSON_HEX_TAG) !!};
            var attachment = {!! json_encode($attachment, JSON_HEX_TAG) !!};
            // END : File and requirements note

        // PAGE LOADED

        @if (!Session::has('displaySMSTag') && !Session::has('errorFormValidation'))
            
            this.getCountryOptions('once');
            this.getCountryTelCode('not-sms');
            this.getItems('once');

            this.fillResubmitContract(contractDetails, attachment);
            this.changeApplicantType(attachment.type);
            this.checkUser('once');

        @endif

        @if (Session::has('displaySMSTag'))
            let networkRequest = {};
            
            this.getCountryTelCode('sms');
            contactOneOfApplicant.value = '{{ session()->get('contact_one_of_applicant') }}';
            telCodeOptions1.value = '{{ session()->get('tel_code_options_1') }}';

            let contactOneSmsVerified = 'invalid'; // 'invalid', 'valid'
            let contactOneSmsTag = document.getElementById('contact-one-sms-tag');
            // END : verification information

            let smsTagSendButton = document.getElementById('sms-tag-send-button');
            let smsTagVerifyButton = document.getElementById('sms-tag-verify-button');  
            let smsTagSubmitButton = document.getElementById('sms-tag-submit-button');
            let smsStatusButton = document.getElementById('sms-status-button');

            let smsState = 'Unverified'; // 'Unverified' , 'SMS sent', 'Verified'
            let smsTimerCountdown = 600; // 10 minutes
            let smsTimeInterval;

            this.fillPreviousRequestData();
        @endif

        @if (Session::has('errorFormValidation'))
            let applicantType = '{{ session()->get('applicant_type') }}';

            this.getCountryOptions('error');
            this.getCountryTelCode('not-sms-error');
            this.getItems('error');
            this.checkUser('error');

            monthOptions.value = '{{ session()->get('no_of_installment_month') }}';
            nameOfApplicant.value = '{{ session()->get('name_of_applicant') }}';      
            icNumber.value = '{{ session()->get('ic_number') }}';           
            telCodeOptions1.value = '{{ session()->get('tel_code_options_1') }}';    
            contactOneOfApplicant.value = '{{ session()->get('contact_one_of_applicant') }}';         
            telCodeOptions2.value = '{{ session()->get('tel_code_options_2') }}';      
            contactTwoOfApplicant.value = '{{ session()->get('contact_two_of_applicant') }}';           
            emailOfApplicant.value = '{{ session()->get('email_of_applicant') }}';           
            addressOne.value = '{{ session()->get('address_one') }}';           
            addressTwo.value = '{{ session()->get('address_two') }}';           
            postcode.value = '{{ session()->get('postcode') }}';           
            nameOfReference.value = '{{ session()->get('name_of_reference') }}';           
            contactOfReference.value = '{{ session()->get('contact_of_reference') }}';  

            if (applicantType == 'individual_applicant') {
                radioIndividualApplicant.checked = true;
            } else if (applicantType == 'self_employed') {
                radioSelfEmployed.checked = true;
            }

            this.changeApplicantType(applicantType);
        @endif
              
        function fillPreviousRequestData() {
            networkRequest.product = '{{ Session::get('product') }}';
            networkRequest.no_of_installment_month = '{{ Session::get('no_of_installment_month') }}';
            networkRequest.name_of_applicant = '{{ Session::get('name_of_applicant') }}';
            networkRequest.ic_number = '{{ Session::get('ic_number') }}';
            networkRequest.tel_code_1 = '{{ Session::get('tel_code_options_1') }}';
            networkRequest.contact_one_of_applicant = '{{ Session::get('contact_one_of_applicant') }}';
            networkRequest.tel_code_2 = '{{ Session::get('tel_code_options_2') }}';
            networkRequest.contact_two_of_applicant = '{{ Session::get('contact_two_of_applicant') }}';
            networkRequest.email_of_applicant = '{{ Session::get('email_of_applicant') }}';
            networkRequest.applicant_type = '{{ Session::get('applicant_type') }}';
            networkRequest.address_one = '{{ Session::get('address_one') }}';
            networkRequest.address_two = '{{ Session::get('address_two') }}';
            networkRequest.postcode = '{{ Session::get('postcode') }}';
            networkRequest.city = '{{ Session::get('city') }}';
            networkRequest.state = '{{ Session::get('state') }}';
            networkRequest.country = '{{ Session::get('country') }}';
            networkRequest.name_of_reference = '{{ Session::get('name_of_reference') }}';
            networkRequest.contact_of_reference = '{{ Session::get('contact_of_reference') }}';
            networkRequest.seller_one = '{{ Session::get('seller_one') }}';
            networkRequest.seller_two = '{{ Session::get('seller_two') }}';
            networkRequest.tandcitsu = '{{ Session::get('tandcitsu') }}';
            networkRequest.tandcctos = '{{ Session::get('tandcctos') }}';
            networkRequest.file_inclusion = 'exclude';
            networkRequest.unit_price = '{{ Session::get('unit_price') }}';
            networkRequest.previous_applicant_type = '{{ Session::get('previous_applicant_type') }}';
            networkRequest.file_individual_icno = '{{ Session::get('file_individual_icno') }}';
            networkRequest.file_individual_income = '{{ Session::get('file_individual_income') }}';
            networkRequest.file_individual_bankstatement = '{{ Session::get('file_individual_bankstatement') }}';
            networkRequest.file_company_form = '{{ Session::get('file_company_form') }}';
            networkRequest.file_company_icno = '{{ Session::get('file_company_icno') }}';
            networkRequest.file_company_bankstatement = '{{ Session::get('file_company_bankstatement') }}';

            console.log(networkRequest);
        }

        function reSubmitFinalForm(id) {
            smsTagSubmitButton.classList.add('disabled');
            smsTagSubmitButton.disabled = true;
            fetch('{{ url('') }}' + `/customer/api/resubmit/` + id, {
                method: 'POST', // or 'PUT'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(networkRequest)
                })
                .then((response) => { return response.json(); })
                .then((res) => { 
                    if (res.status === 'success') {
                        localStorage.removeItem('referrerCode');
                        window.location.href = res.redirect;
                    }
                })
                .catch((error) => {
                    console.log(['err', error]);
                    showAlert('There is something wrong in the server! Please contact admin', 'alert-danger');
                    smsTagSubmitButton.classList.remove('disabled');
                    smsTagSubmitButton.disabled = false;
                });
        }

        function changeSmsState(state) {
            smsState = state;
            smsStatusButton.innerText = 'Status : ' + state;
        }

        function clickSendSmsTag() {
            this.sendSmsTag();
        }

        function toggleRequirements(className) {
            let elements = document.getElementsByClassName(className);

            for (el of elements) {
                el.classList.toggle("hide");
            }
        }

        function fillResubmitContract(data, attachment) {
            
            rejectReason.value = data.CNH_RejectDesc;
            rejectDate.value = data.CNH_RejectDate;
            nameOfApplicant.value = data.Cust_NAME;
            icNumber.value = data.Cust_NRIC;
            contactOneOfApplicant.value = data.Cust_Phone1;
            contactTwoOfApplicant.value = data.Cust_Phone2;
            emailOfApplicant.value = data.Cust_Email;
            addressOne.value = data.CNH_Address1;
            addressTwo.value = data.CNH_Address2;
            postcode.value = data.CNH_Postcode;
            nameOfReference.value = data.CNH_NameRef;
            contactOfReference.value = data.CNH_ContactRef;

            if (attachment.type === 'individual_applicant') {
                radioIndividualApplicant.checked = true;
            } else if (attachment.type === 'self_employed') {
                radioSelfEmployed.checked = true;
            }
        }
        
        function removeOptions(option) {
            while (option.hasChildNodes()) {
                option.removeChild(option.firstChild);
            }
        }

        function removeUnitPrice() {
            unitPrice.value = null;
        }

        function sendSmsTag() {
            smsTagSendButton.classList.add('disabled');
            smsTagSendButton.disabled = true;
            fetch('{{ url('') }}' + `/customer/api/sms/send`, {
                method: 'POST', // or 'PUT'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(
                    {
                        'tel_code_1' : telCodeOptions1.value,
                        'contact_one_of_applicant' : contactOneOfApplicant.value }
                )
                })
                .then((response) => { return response.json() })
                .then((res) => {
                    this.changeSmsState('SMS Sent');
                    smsTimerCountdown = 120;
                    smsTimeInterval = setInterval(function () {
                        smsTimerCountdown--;
                        let secs = smsTimerCountdown % 60;
                        let mins = Math.floor(smsTimerCountdown / 60);

                        smsTagSendButton.innerText = "Verification SMS is sent. Expired in : " + mins.toString().padStart(2, '0') + ":" + secs.toString().padStart(2, '0');

                        if (smsTimerCountdown === 1) {
                            clearInterval(smsTimeInterval);
                            smsTagSendButton.classList.remove('disabled');
                            smsTagSendButton.disabled = false;
                            smsTagSendButton.innerText = "Send SMS Tag";

                            this.changeSmsState('Unverified')
                        }
                    }, 1000);
                })
                .catch((error) => {
                    smsTagSendButton.classList.remove('disabled');
                    smsTagSendButton.disabled = false;
                    smsTagSendButton.innerText = "Send SMS Tag";

                    showAlert('Invalid phone number, please include country code!', 'alert-danger');
                    console.log(['error', error]);
                });
        }

        function verifySmsTag() {
            smsTagVerifyButton.classList.add('disabled'); // disable verify button
            smsTagVerifyButton.disabled = true;
            fetch('{{ url('') }}' + `/customer/api/sms/verify`, {
                method: 'POST', // or 'PUT'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(
                    {
                        'contact_one_of_applicant' : contactOneOfApplicant.value,
                        'tel_code_1' : telCodeOptions1.value,
                        'contact_one_sms_tag': contactOneSmsTag.value
                    }
                )
                })
                .then((response) => { return response.json(); })
                .then((res) => {
                    if (res.status === 'approved') {
                        this.changeSmsState('Approved');
                        smsTagSubmitButton.classList.remove('disabled');
                        smsTagSubmitButton.disabled = false;

                        networkRequest.contact_one_sms_tag = contactOneSmsTag.value;
                        networkRequest.contact_one_sms_verified = 'valid';
                    } else {
                        this.changeSmsState('Invalid Tag');
                        smsTagVerifyButton.classList.remove('disabled'); // disable verify button
                        smsTagVerifyButton.disabled = false;
                    }
                })
                .catch((error) => {
                    console.log(['err', error]);
                });
        }

        function checkUser(type) {
            fetch('{{ url('') }}' + `/customer/api/check/user`, {
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
                    this.clearItems(sellerOne);
                    this.clearItems(sellerTwo);
                    let optionOne = document.createElement('option');
                    optionOne.setAttribute('value', "");
                    optionOne.appendChild(document.createTextNode(''));

                    sellerOne.appendChild(optionOne);

                    let optionTwo = document.createElement('option');
                    optionTwo.setAttribute('value', "");
                    optionTwo.appendChild(document.createTextNode(''));

                    sellerTwo.appendChild(optionTwo);

                    for (let each of res.data) {
                        let optionOne = document.createElement('option');
                        optionOne.setAttribute('value', each.id);
                        optionOne.appendChild(document.createTextNode(each.name));

                        sellerOne.appendChild(optionOne);

                        let optionTwo = document.createElement('option');
                        optionTwo.setAttribute('value', each.id);
                        optionTwo.appendChild(document.createTextNode(each.name));

                        sellerTwo.appendChild(optionTwo);
                    }
                    
                    if (!res.staff) {
                        sellerOne.disabled = true;
                        sellerTwo.disabled = true;
                        let input = document.createElement("input");
                        input.setAttribute("type", "hidden");
                        input.setAttribute("name", "seller_one");
                        input.setAttribute("value", (contractDetails.CNH_SalesAgent1) ? contractDetails.CNH_SalesAgent1 : "");

                        let input2 = document.createElement("input");
                        input2.setAttribute("type", "hidden");
                        input2.setAttribute("name", "seller_two");
                        input2.setAttribute("value", (contractDetails.CNH_SalesAgent2) ? contractDetails.CNH_SalesAgent2 : "");

                        //append to form element that you want .
                        form.appendChild(input);
                        form.appendChild(input2);
                    }

                    if (type == 'once') {
                        sellerOne.value = (contractDetails.CNH_SalesAgent1) ? (contractDetails.CNH_SalesAgent1) : "";
                        sellerTwo.value = (contractDetails.CNH_SalesAgent2) ? (contractDetails.CNH_SalesAgent2) : "";
                    }

                    @if (Session::has('errorFormValidation'))
                        sellerOne.value = '{{ session()->get('seller_one') }}';
                        sellerTwo.value = '{{ session()->get('seller_two') }}';
                    @endif
                })
                .catch((error) => {
                    console.log(['err', error]);
                });
        }

        function getItems(type) {
            fetch('{{ url('') }}' + `/customer/api/items`, {
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
                    this.clearItems(itemOptions);
                    let option = document.createElement('option');
                    option.setAttribute('value', '');
                    option.appendChild(document.createTextNode('-- Select Product --'));

                    itemOptions.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.IM_ID);
                        option.appendChild(document.createTextNode(each.IM_Description));
                        itemOptions.appendChild(option);
                    }

                    if (type == 'once') {
                        itemOptions.value = contractDetails.CND_ItemID;
                        this.populateMonthOptions(itemOptions);
                    }

                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        itemOptions.value = '{{ session()->get('product') }}';
                        this.populateMonthOptions(itemOptions);
                    @endif
                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateMonthOptions(option, change) {
            fetch('{{ url('') }}' + `/customer/api/items/rental?item_id=` + option.value, {
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
                    this.removeOptions(monthOptions);
                    this.removeUnitPrice();

                    let option = document.createElement('option');
                    option.setAttribute('value', '');
                    option.appendChild(document.createTextNode('-- Select Rental Month Options --'));

                    monthOptions.appendChild(option);
                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.IR_OptionKey);
                        option.appendChild(document.createTextNode(each.IR_OptionDesc));

                        monthOptions.appendChild(option);
                    }

                    if (change != 'change') {
                        monthOptions.value = contractDetails.CNH_TotInstPeriod;
                        unitPrice.value = contractDetails.CND_UnitPrice;
                    }

                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        monthOptions.value = '{{ session()->get('no_of_installment_month') }}';
                        unitPrice.value = '{{ session()->get('unit_price') }}';
                    @endif

                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateUnitPrice(product, option) {
            fetch('{{ url('') }}' + `/customer/api/items/rental/price?item_id=` + product.value + `&option_key=` + option.value, {
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
                    // unitPrice
                    this.removeUnitPrice();
                    unitPrice.value = res.data.IR_UnitPrice;
                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateCities(option, change) {
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
                    this.removeOptions(cityOptions);

                    let option = document.createElement('option');
                    option.setAttribute('value', '');
                    option.appendChild(document.createTextNode('-- Select City --'));

                    cityOptions.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.CI_ID);
                        option.appendChild(document.createTextNode(each.CI_Description));

                        cityOptions.appendChild(option);
                    }
                    
                    if (change != 'change') {
                        cityOptions.value = contractDetails.CNH_City;
                    }

                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        cityOptions.value = '{{ session()->get('city') }}';
                    @endif

                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateStates(option, change) {
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
                    this.removeOptions(cityOptions);
                    this.removeOptions(stateOptions);

                    let option = document.createElement('option');
                    option.setAttribute('value', '');
                    option.appendChild(document.createTextNode('-- Select State --'));

                    stateOptions.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.ST_ID);
                        option.appendChild(document.createTextNode(each.ST_Description));

                        stateOptions.appendChild(option);
                    }

                    if (change != 'change') {
                        stateOptions.value = contractDetails.CNH_State;
                        this.populateCities(stateOptions);
                    }
                    
                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        stateOptions.value = '{{ session()->get('state') }}';
                        this.populateCities(stateOptions, 'change');
                    @endif
                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function getCountryOptions(type) {
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
                    this.clearItems(countryOptions);
                    let option = document.createElement('option');
                    option.setAttribute('value', '');
                    option.appendChild(document.createTextNode('-- Select Country --'));

                    countryOptions.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.CO_ID);
                        option.appendChild(document.createTextNode(each.CO_Description));

                        countryOptions.appendChild(option);
                    }

                    if (type == 'once') {
                        countryOptions.value = contractDetails.CNH_Country;
                        this.populateStates(countryOptions);
                    }

                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        countryOptions.value = '{{ session()->get('country') }}';
                        this.populateStates(countryOptions, 'change');
                    @endif
                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function getCountryTelCode(type) {
            fetch("{{ url('/customer/api/country/tel-code') }}", {
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
                    if (type === 'sms') {
                        this.clearItems(telCodeOptions1);
                        let option = document.createElement('option');
                        option.setAttribute('value', '');
                        option.appendChild(document.createTextNode('-- Select Tel Code --'));
                        
                        for (let each of res.data) {
                            let option = document.createElement('option');
                            option.setAttribute('value', each.dial_code);
                            option.appendChild(document.createTextNode(`${each.name} (${each.dial_code})`));

                            telCodeOptions1.appendChild(option);
                        }
                        
                        telCodeOptions1.value = '{{ session()->get('tel_code_options_1') }}';    
                    } else {
                        this.clearItems(telCodeOptions1);
                        this.clearItems(telCodeOptions2);
                        let option = document.createElement('option');
                        option.setAttribute('value', '');
                        option.appendChild(document.createTextNode('-- Select Tel Code --'));
                        let option2 = document.createElement('option');
                        option2.setAttribute('value', '');
                        option2.appendChild(document.createTextNode('-- Select Tel Code --'));

                        telCodeOptions1.appendChild(option);
                        telCodeOptions2.appendChild(option2);

                        for (let each of res.data) {
                            let option = document.createElement('option');
                            option.setAttribute('value', each.dial_code);
                            option.appendChild(document.createTextNode(`${each.name} (${each.dial_code})`));

                            let option2 = document.createElement('option');
                            option2.setAttribute('value', each.dial_code);
                            option2.appendChild(document.createTextNode(`${each.name} (${each.dial_code})`));

                            telCodeOptions1.appendChild(option);
                            telCodeOptions2.appendChild(option2);
                        }

                        if (type == 'not-sms') {
                            telCodeOptions1.value = contractDetails.telcode1;
                            telCodeOptions2.value = contractDetails.telcode2;
                        }

                        // if got error validation
                        @if (Session::has('errorFormValidation'))
                            telCodeOptions1.value = '{{ session()->get('tel_code_options_1') }}';
                            telCodeOptions2.value = '{{ session()->get('tel_code_options_2') }}';
                        @endif  
                    }

                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }
        
        function clearItems(item){
            for (i = item.options.length-1; i >= 0; i--) {
                item.options[i] = null;
            }
        }

        function changeApplicantType(type) {
            this.hideAllApplicantType();

            if (type === 'individual_applicant') {
                individualApplicantRequirement.classList.remove('hide');
                individualApplicantNotes.classList.remove('hide');

                if (attachment.type === 'self_employed') {
                    individualApplicantIC.required = true;
                    individualApplicantIncome.required = true;
                    individualApplicantBankStatement.required = true;
                    selfEmployedForm.required = false;
                    selfEmployedIC.required = false;
                    selfEmployedBankStatement.required = false;
                } else {
                    selfEmployedForm.required = false;
                    selfEmployedIC.required = false;
                    selfEmployedBankStatement.required = false;
                    previousIndividualApplicant.classList.remove('hide');
                }
            } else if (type === 'self_employed') {
                selfEmployedRequirement.classList.remove('hide');
                selfEmployedNotes.classList.remove('hide');

                if (attachment.type === 'individual_applicant') {
                    selfEmployedForm.required = true;
                    selfEmployedIC.required = true;
                    selfEmployedBankStatement.required = true;
                    individualApplicantIC.required = false;
                    individualApplicantIncome.required = false;
                    individualApplicantBankStatement.required = false;
                } else {
                    individualApplicantIC.required = false;
                    individualApplicantIncome.required = false;
                    individualApplicantBankStatement.required = false;
                    previousSelfEmployed.classList.remove('hide');
                }
            }
        }

        function hideAllApplicantType() {

            individualApplicantRequirement.classList.add('hide');
            selfEmployedRequirement.classList.add('hide');
            individualApplicantNotes.classList.add('hide');
            selfEmployedNotes.classList.add('hide');

            if (attachment.type === 'individual_applicant') {
                previousIndividualApplicant.classList.add('hide');
            } else {
                previousSelfEmployed.classList.add('hide');
            }
        }
        
        function unhideRequirement() {
            let elements0 = document.getElementsByClassName('product-installment');
            let elements1 = document.getElementsByClassName('personal-information');
            let elements2 = document.getElementsByClassName('referral-information');
            let elements3 = document.getElementsByClassName('self-employed-requirement');
            let elements4 = document.getElementsByClassName('employed-requirement');
            let elements5 = document.getElementsByClassName('reject-information');

            for (el of elements0) { el.classList.remove('hide') };
            for (el of elements1) { el.classList.remove('hide') };
            for (el of elements2) { el.classList.remove('hide') };
            
            if (radioSelfEmployed.checked == true) {
                for (el of elements3) { el.classList.remove('hide') };
            } else if (radioIndividualApplicant.checked == true) {
                for (el of elements4) { el.classList.remove('hide') };
            } else {
                for (el of elements3) { el.classList.remove('hide') };
                for (el of elements4) { el.classList.remove('hide') };
            }

            for (el of elements5) { el.classList.remove('hide') };
        }
    </script>
@endsection
