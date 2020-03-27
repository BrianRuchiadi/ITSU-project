@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/customer/application-form.css">
@endsection

@section('content')
    <h2 class="center">Application Form</h2>
    <form method="POST" action="{{ url('/api/apply') }}" enctype="multipart/form-data" id="form">
        {{ csrf_field() }}

        @if (!Session::has('displaySMSTag'))
            <h3 class="section-header" onclick="toggleRequirements('product-installment')">
                1. Product Installment
                <i class="fas fa-caret-down right"></i>
            </h3>
            <section class="group product-installment">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Product</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="item-options" name="product" onchange="populateMonthOptions(this)" required>
                        </select>
                    @error('product')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">No Of Installment Month</label>
                    <div class="col-sm-8">
                    <select class="form-control" id="month-options" name="no_of_installment_month" onchange="populateUnitPrice(product, this)" required>
                    </select>   
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
                        <input type="text" class="form-control" id="unit-price" name="unit_price" readonly>
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
            
            <h3 class="section-header" onclick="toggleRequirements('personal-information')">
                2. Personal Information
                <i class="fas fa-caret-down right"></i>
            </h3>
            <section class="group personal-information">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Name Of Applicant</label>
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
                    <label class="col-sm-4 col-form-label">IC Number</label>
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
                    <label class="col-sm-4 col-form-label">Contact 1 Of Applicant<br/>
                        <strong>(Will be used for SMS Tag) </strong>
                    </label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="+60 15 666 XXXX" id="contact-one-of-applicant" name="contact_one_of_applicant" required>
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
                    <input type="text" class="form-control" placeholder="+60 15 666 XXXX" id="contact-two-of-applicant" name="contact_two_of_applicant">
                    @error('contact_two_of_applicant')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Email Of Applicant</label>
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
                    <label class="col-sm-4 col-form-label">Address 1</label>
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
                    <label class="col-sm-4 col-form-label">Postcode</label>
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
                    <label class="col-sm-4 col-form-label">Country</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="country-options" onchange="populateStates(this)" name="country" required>
                        </select>
                    @error('country')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">State</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="state-options" onchange="populateCities(this)" name="state" required>
                        </select>
                    @error('state')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror 
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">City</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="city-options" name="city" required>
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

            <input type="hidden" name="file_inclusion" value="include">

            <h3 class="section-header" onclick="toggleRequirements('referral-information')">
                3. Referral Information
                <i class="fas fa-caret-down right"></i>
            </h3>
            <section class="group referral-information">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Seller 1</label>
                    <div class="col-sm-8">
                    <select class="form-control" id="seller-one" name="seller_one" required>
                    </select>
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
                    <select class="form-control" id="seller-two" name="seller_two">
                    </select>
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
                    <label class="col-sm-8 col-form-label">One Photocopy Of I/C</label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" name="file_individual_icno">
                        @error('file_individual_icno')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                    
                </div>

                <div class="form-group row employed-requirement">
                    <label class="col-sm-8 col-form-label">Photocopy Of 3 Months Proof Of Income</label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" name="file_individual_income">
                        @error('file_individual_income')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>

                <div class="form-group row employed-requirement">
                    <label class="col-sm-8 col-form-label">Updated Bank Statement Or Savings Passbook</label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" name="file_individual_bankstatement">
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
                    <label class="col-sm-8 col-form-label">
                        Form J and Business Registration Form<br/>
                        (Borang A and D)
                    </label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" name="file_company_form">
                        @error('file_company_form')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>

                <div class="form-group row self-employed-requirement">
                    <label class="col-sm-8 col-form-label">
                        Photocopied I/C Of Proprietor / Partners / Directors
                    </label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" name="file_company_icno">
                        @error('file_company_icno')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>

                <div class="form-group row self-employed-requirement">
                    <label class="col-sm-8 col-form-label">
                        Updated 3 Month Bank Statement
                    </label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" name="file_company_bankstatement">
                        @error('file_company_bankstatement')
                        <div class="form-alert alert-danger">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror 
                    </div>
                </div>
            </div>

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
                    <button type="submit" class="btn btn-block btn-primary">Submit</button>
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
                        <input type="text" class="form-control" placeholder="6012 333 4444" id="contact-one-of-applicant" name="contact_one_of_applicant" required>
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
                    <button type="button" id="sms-tag-submit-button" class="btn btn-primary btn-block" onclick="submitFinalForm()" disabled>Submit</button>
                </div>
            @endif
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        
        @if (!Session::has('displaySMSTag'))
            let form = document.getElementById('form');
            // START : product installment 
            let itemOptions = document.getElementById('item-options');
            let monthOptions = document.getElementById('month-options');
            let unitPrice = document.getElementById('unit-price');
            // END : product installment

            // START : personal information
            let nameOfApplicant = document.getElementById('name-of-applicant');
            let icNumber = document.getElementById('ic-number');
            let contactOneOfApplicant = document.getElementById('contact-one-of-applicant');
            let contactTwoOfApplicant = document.getElementById('contact-two-of-applicant');
            let emailOfApplicant = document.getElementById('email-of-applicant');
            let addressOne = document.getElementById('address-one');
            let addressTwo = document.getElementById('address-two');
            let postcode = document.getElementById('postcode');
            let countryOptions = document.getElementById('country-options');
            let stateOptions = document.getElementById('state-options');
            let cityOptions = document.getElementById('city-options');
            let nameOfReference = document.getElementById('name-of-reference');
            let contactOfReference = document.getElementById('contact-of-reference');
            let radioSelfEmployed = document.getElementById('radio-self-employed');
            let radioIndividualApplicant = document.getElementById('radio-individual-applicant');
            // END : personal information

            // START : referral information
            let sellerOne = document.getElementById('seller-one');
            let sellerTwo = document.getElementById('seller-two');
            // END : referral information

            // START : File and requirements note
            let individualApplicantRequirement = document.getElementById('individual-applicant-requirement');
            let selfEmployedRequirement = document.getElementById('self-employed-requirement');

            let individualApplicantNotes = document.getElementById('individual-applicant-notes');
            let selfEmployedNotes = document.getElementById('self-employed-notes');
            // END : File and requirements note
            
            let applicantName = '{{ Auth::user()->branchind === 4 ? Auth::user()->name : '' }}';
            let applicantContactOne = '{{ Auth::user()->branchind === 4 ? Auth::user()->telephone : '' }}';
            let applicantEmail = '{{ Auth::user()->branchind === 4 ? Auth::user()->email : ''}}';

            this.getCountryOptions();
            this.getItems();
            this.fillApplicantName();
            this.fillApplicantContactOne();
            this.fillEmailOfApplicant();

            if (localStorage.getItem('referrerCode')) {
                this.getUsers();
            } else {
                this.checkUser();
                sellerOne.disabled = true;
                sellerTwo.disabled = true;
            }
        @endif

        @if (Session::has('displaySMSTag'))
            let networkRequest = {};
            // START : verification information
            let contactOneOfApplicant = document.getElementById('contact-one-of-applicant');
            contactOneOfApplicant.value = '{{ session()->get('contact_one_of_applicant') }}';

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

            this.getCountryOptions();
            this.getItems();

            if (localStorage.getItem('referrerCode')) {
                this.getUsers();
            } else {
                this.checkUser();
                sellerOne.disabled = true;
                sellerTwo.disabled = true;
            }

            monthOptions.value = '{{ session()->get('no_of_installment_month') }}';
            nameOfApplicant.value = '{{ session()->get('name_of_applicant') }}';      
            icNumber.value = '{{ session()->get('ic_number') }}';           
            contactOneOfApplicant.value = '{{ session()->get('contact_one_of_applicant') }}';           
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
            networkRequest.contact_one_of_applicant = '{{ Session::get('contact_one_of_applicant') }}';
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
        }

        function submitFinalForm() {
            smsTagSubmitButton.classList.add('disabled');
            smsTagSubmitButton.disabled = true;
            fetch('{{ url('') }}' + `/api/apply`, {
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
                        location.reload();
                    }
                })
                .catch((error) => {
                    console.log(['err', error]);
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

        function removeStates() {
            while (stateOptions.hasChildNodes()) {  
                stateOptions.removeChild(stateOptions.firstChild);
            }
        }

        function removeCities() {
            while (cityOptions.hasChildNodes()) {  
                cityOptions.removeChild(cityOptions.firstChild);
            }
        }

        function removeMonthOptions() {
            while (monthOptions.hasChildNodes()) {  
                monthOptions.removeChild(monthOptions.firstChild);
            }
        }

        function removeUnitPrice() {
            unitPrice.value = '';
        }

        function fillApplicantName() {
            nameOfApplicant.value = applicantName;
        }

        function fillApplicantContactOne() {
            contactOneOfApplicant.value = applicantContactOne;
        }

        function fillEmailOfApplicant() {
            emailOfApplicant.value = applicantEmail;
        }

        function sendSmsTag() {
            smsTagSendButton.classList.add('disabled');
            smsTagSendButton.disabled = true;
            fetch('{{ url('') }}' + `/api/sms/send`, {
                method: 'POST', // or 'PUT'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(
                    {'contact_one_of_applicant' : contactOneOfApplicant.value }
                )
                })
                .then((response) => { return response.json() })
                .then((res) => {
                    this.changeSmsState('SMS Sent');
                    smsTimerCountdown = 600;
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
                    console.log(['error', error]);
                });
        }

        function verifySmsTag() {
            smsTagVerifyButton.classList.add('disabled'); // disable verify button
            smsTagVerifyButton.disabled = true;
            fetch('{{ url('') }}' + `/api/sms/verify`, {
                method: 'POST', // or 'PUT'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(
                    {
                        'contact_one_of_applicant' : contactOneOfApplicant.value,
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

        function getUsers() {
            fetch('{{ url('') }}' + `/api/users?ref=` + localStorage.getItem('referrerCode'), {
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

                    @if (Session::has('errorFormValidation'))
                        sellerOne.value = '{{ session()->get('seller_one') }}';
                        sellerTwo.value = '{{ session()->get('seller_two') }}';
                    @endif
                    
                    if (res.decoded_referrer_id) {
                        sellerOne.value = res.decoded_referrer_id;
                        sellerOne.setAttribute("disabled", true);
                        sellerTwo.setAttribute("disabled", true);

                        let input = document.createElement("input");
                        input.setAttribute("type", "hidden");
                        input.setAttribute("name", "seller_one");
                        input.setAttribute("value", res.decoded_referrer_id);
                        
                        let input2 = document.createElement("input");
                        input2.setAttribute("type", "hidden");
                        input2.setAttribute("name", "seller_two");
                        input2.setAttribute("value", "");
                        
                        //append to form element that you want .
                        form.appendChild(input);
                        form.appendChild(input2);
                    }
                })
                .catch((error) => {
                    console.log(['err', error]);
                });
        }

        function checkUser() {
            fetch('{{ url('') }}' + `/api/check/user`, {
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

                    @if (Session::has('errorFormValidation'))
                        sellerOne.value = '{{ session()->get('seller_one') }}';
                        sellerTwo.value = '{{ session()->get('seller_two') }}';
                    @endif
                    
                    if (res.staff) {
                        sellerOne.value = res.staff;
                        sellerOne.setAttribute("disabled", true);
                        sellerTwo.setAttribute("disabled", true);

                        let input = document.createElement("input");
                        input.setAttribute("type", "hidden");
                        input.setAttribute("name", "seller_one");
                        input.setAttribute("value", res.staff);
                        
                        let input2 = document.createElement("input");
                        input2.setAttribute("type", "hidden");
                        input2.setAttribute("name", "seller_two");
                        input2.setAttribute("value", "");
                        
                        //append to form element that you want .
                        form.appendChild(input);
                        form.appendChild(input2);
                    }
                })
                .catch((error) => {
                    console.log(['err', error]);
                });
        }

        function getItems() {
            fetch('{{ url('') }}' + `/api/items`, {
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
                    option.setAttribute('value', 0);
                    option.appendChild(document.createTextNode(''));

                    itemOptions.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.IM_ID);
                        option.appendChild(document.createTextNode(each.IM_Description));
                        itemOptions.appendChild(option);
                    }

                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        itemOptions.value = '{{ session()->get('product') }}';
                        this.populateMonthOptions(itemOptions)
                    @endif
                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateMonthOptions(option) {
            fetch('{{ url('') }}' + `/api/items/rental?item_id=` + option.value, {
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
                    // monthOptions
                    this.removeMonthOptions();
                    this.removeUnitPrice();

                    let option = document.createElement('option');
                    option.setAttribute('value', 0);
                    option.appendChild(document.createTextNode(''));

                    monthOptions.appendChild(option);
                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.IR_OptionKey);
                        option.appendChild(document.createTextNode(each.IR_OptionDesc));

                        monthOptions.appendChild(option);
                    }
                    
                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        monthOptions.value = '{{ session()->get('no_of_installment_month') }}';
                        this.populateUnitPrice(itemOptions, monthOptions);
                    @endif

                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateUnitPrice(product, option) {
            fetch('{{ url('') }}' + `/api/items/rental/price?item_id=` + product.value + `&option_key=` + option.value, {
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

        function populateStates(option) {
            // text : option.options[option.selectedIndex].innerHTML,
            // value : option.value
            fetch('{{ url('') }}' + `/api/country/states?co_id=` + option.value, {
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
                    this.removeCities();
                    this.removeStates();

                    let option = document.createElement('option');
                    option.setAttribute('value', 0);
                    option.appendChild(document.createTextNode(''));

                    stateOptions.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.ST_ID);
                        option.appendChild(document.createTextNode(each.ST_Description));

                        stateOptions.appendChild(option);
                    }
                    
                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        stateOptions.value = '{{ session()->get('state') }}';
                        this.populateCities(stateOptions);
                    @endif

                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateCities(option) {
            fetch('{{ url('') }}' + `/api/state/cities?st_id=` + option.value , {
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
                    this.removeCities();

                    let option = document.createElement('option');
                    option.setAttribute('value', 0);
                    option.appendChild(document.createTextNode(''));

                    cityOptions.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.CI_ID);
                        option.appendChild(document.createTextNode(each.CI_Description));

                        cityOptions.appendChild(option);
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

        function getCountryOptions() {
            fetch("{{ url('/api/countries') }}", {
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
                    option.setAttribute('value', 0);
                    option.appendChild(document.createTextNode(''));

                    countryOptions.appendChild(option);

                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.CO_ID);
                        option.appendChild(document.createTextNode(each.CO_Description));

                        countryOptions.appendChild(option);
                    }

                    // if got error validation
                    @if (Session::has('errorFormValidation'))
                        countryOptions.value = '{{ session()->get('country') }}';
                        this.populateStates(countryOptions);
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

        function changeApplicantType(type) {
            this.hideAllApplicantType();

            if (type === 'individual_applicant') {
                individualApplicantRequirement.classList.remove('hide');
                individualApplicantNotes.classList.remove('hide');
            } else if (type === 'self_employed') {
                selfEmployedRequirement.classList.remove('hide');
                selfEmployedNotes.classList.remove('hide');
            }
        }

        function hideAllApplicantType() {
            individualApplicantRequirement.classList.add('hide');
            selfEmployedRequirement.classList.add('hide');
            individualApplicantNotes.classList.add('hide');
            selfEmployedNotes.classList.add('hide');
        }
    </script>
@endsection
