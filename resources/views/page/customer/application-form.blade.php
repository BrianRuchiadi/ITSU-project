@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/customer/application-form.css">
@endsection

@section('content')
    <h2 class="center">Application Form</h2>
    <form>
        <h3 class="section-header" onclick="toggleRequirements('product-installment')">
            1. Product Installment
            <i class="fas fa-caret-down right"></i>
        </h3>
        <section class="group product-installment">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Product</label>
                <div class="col-sm-8">
                    <select class="form-control" id="item-options" id="product" name="product" required>
                    </select>
                </div>
            </div>
      
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">No Of Installment Month</label>
                <div class="col-sm-8">
                  <input type="number" class="form-control" placeholder="No Of Installment Month" id="no-of-installment-month" name="no_of_installment_month" required>
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
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">IC Number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="IC Number" id="ic-number" name="ic_number" required>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Contact 1 Of Applicant<br/>
                    <strong>(Will be used for SMS Tag) </strong>
                </label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="6012 333 4444" id="contact-one-of-applicant" name="contact_one_of_applicant" required>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Contact 2 Of Applicant</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="+60 15 666 XXXX" id="contact-two-of-applicant" name="contact_two_of_applicant">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Email Of Applicant</label>
                <div class="col-sm-8">
                  <input type="email" class="form-control" placeholder="jane.doe@gmail.com" id="email-of-applicant" name="email_of_applicant" required>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Address 1</label>
                <div class="col-sm-8">
                    <textarea class="form-control" placeholder="Address 1" id="address-one" name="address_one" required>
                    </textarea>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Address 2</label>
                <div class="col-sm-8">
                    <textarea class="form-control" placeholder="Address 1" id="address-two" name="address_two">
                    </textarea>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Postcode</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Postcode" id="postcode" name="postcode" required>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Country</label>
                <div class="col-sm-8">
                    <select class="form-control" id="country-options" onchange="populateStates(this)" name="country" required>
                    </select>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">State</label>
                <div class="col-sm-8">
                    <select class="form-control" id="state-options" onchange="populateCities(this)" name="state" required>
                    </select>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">City</label>
                <div class="col-sm-8">
                    <select class="form-control" id="city-options" name="city" required>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Name Of Reference</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Name Of Reference" id="name-of-reference" name="name_of_reference">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Contact of Reference</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Contact Of Reference" id="contact-of-reference" name="contact_of_reference">
                </div>
            </div>
        </section>

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
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Seller 2</label>
                <div class="col-sm-8">
                  <select class="form-control" id="seller-two" name="seller_two">
                  </select>
                </div>
            </div>
        </section>

        <h3 class="important-note" onclick="toggleRequirements('employed-requirement')">
            Individual Applicant
            <i class="fas fa-caret-down"></i>
        </h3>
        <div class="form-group row employed-requirement">
            <label class="col-sm-8 col-form-label">One Photocopy Of I/C</label>
            <div class="col-sm-4">
              <input type="file" class="form-control">
            </div>
        </div>

        <div class="form-group row employed-requirement">
            <label class="col-sm-8 col-form-label">Photocopy Of 3 Months Proof Of Income</label>
            <div class="col-sm-4">
              <input type="file" class="form-control">
            </div>
        </div>

        <div class="form-group row employed-requirement">
            <label class="col-sm-8 col-form-label">Updated Bank Statement Or Savings Passbook</label>
            <div class="col-sm-4">
              <input type="file" class="form-control">
            </div>
        </div>

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
              <input type="file" class="form-control">
            </div>
        </div>

        <div class="form-group row self-employed-requirement">
            <label class="col-sm-8 col-form-label">
                Photocopied I/C Of Proprietor / Partners / Directors
            </label>
            <div class="col-sm-4">
              <input type="file" class="form-control">
            </div>
        </div>

        <div class="form-group row self-employed-requirement">
            <label class="col-sm-8 col-form-label">
                Updated 3 Month Bank Statement
            </label>
            <div class="col-sm-4">
              <input type="file" class="form-control">
            </div>
        </div>

        <div class="notes">
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

        <div class="form-group row" style="margin-top: 10px;">
            <div class="col-sm-9">
                <div>
                    <input type="checkbox" id="tandcitsu" value="1" required>
                    <label for="tandc">I have read & agree to the <a href="https://www.google.com" target="blank" class="tandc">Terms & Condition</a></label>

                    @error('tandcitsu')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <input type="checkbox" id="tandcctos" value="1" required>
                    <label for="tandc">I have read & agree with <a href="https://www.google.com" target="blank" class="tandc">CTOS Consent Authorization</a></label>

                    @error('tandcctos')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
            </div>
            <div class="col-sm-3" style="margin: auto;">
                <button type="submit" class="btn btn-block btn-primary">Submit</button>
            </div>
        </div>

        <!--
        <h3 class="section-header" onclick="toggleRequirements('verification-information')">
            Verification Information
            <button class="btn btn-warning" id="sms-status-button" style="cursor: not-allowed">Status : </button>
            <i class="fas fa-caret-down right"></i>
        </h3>
        <section class="group verification-information">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-sm-4 col-form-label">Contact 1 SMS tag</label>
                </div>
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="SMS tag" name="contact_one_sms_tag" id="contact-one-sms-tag">
                    <button type="button" class="btn btn-block btn-success" onclick="verifySmsTag()" id="sms-tag-verify-button">Verify</button>
                </div>
            </div>
        </section>

        <div class="form-group row last">
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </div>
        -->
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        let countryOptions = document.getElementById('country-options');
        let stateOptions = document.getElementById('state-options');
        let cityOptions = document.getElementById('city-options');
        
        let itemOptions = document.getElementById('item-options');
        let nameOfApplicant = document.getElementById('name-of-applicant');
        let contactOneOfApplicant = document.getElementById('contact-one-of-applicant');
        let contactOneSmsTag = document.getElementById('contact-one-sms-tag');
        let emailOfApplicant = document.getElementById('email-of-applicant');

        let sellerOne = document.getElementById('seller-one');
        let sellerTwo = document.getElementById('seller-two');

        let smsTagSendButton = document.getElementById('sms-tag-send-button');
        let smsTagVerifyButton = document.getElementById('sms-tag-verify-button');  
        let smsStatusButton = document.getElementById('sms-status-button');

        let smsState = 'Unverified'; // 'Unverified' , 'SMS sent', 'Verified'
        let smsTimerCountdown = 600; // 10 minutes
        let smsTimeInterval;

        let applicantName = '{{ Auth::user()->branchind === 4 ? Auth::user()->name : '' }}';
        let applicantContactOne = '{{ Auth::user()->branchind === 4 ? Auth::user()->telephone : '' }}';
        let applicantEmail = '{{ Auth::user()->branchind === 4 ? Auth::user()->email : ''}}';

        this.getCountryOptions();
        this.getItems();
        this.fillApplicantName();
        this.fillApplicantContactOne();
        this.fillEmailOfApplicant();
        this.getUsers();

        function changeSmsState(state) {
            smsState = state;
            smsStatusButton.innerText = 'Status : ' + state;
        }

        function clickSendSmsTag() {
            smsTimerCountdown = 600;

            this.changeSmsState('SMS Sent');
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
                .then((response) => { return response.json(); })
                .then((res) => {
                    smsTimeInterval = setInterval(function () {
                        smsTimerCountdown--;
                        let secs = smsTimerCountdown % 60;
                        let mins = Math.floor(smsTimerCountdown / 60);

                        smsTagSendButton.classList.add('disabled');
                        smsTagSendButton.innerText = "Verification SMS is sent. Expired in : " + mins.toString().padStart(2, '0') + ":" + secs.toString().padStart(2, '0');

                        if (smsTimerCountdown === 1) {
                            clearInterval(smsTimeInterval);
            
                            smsTagSendButton.classList.remove('disabled');
                            smsTagSendButton.innerText = "Send SMS Tag";

                            this.changeSmsState('Unverified')
                        }
                    }, 1000);
                })
                .catch((error) => {
                    console.log(['err', error]);
                });
        }

        function verifySmsTag() {
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
                        smsTagVerifyButton.classList.add('disabled'); // disable verify button
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
                    let optionOne = document.createElement('option');
                    optionOne.setAttribute('value', 0);
                    optionOne.appendChild(document.createTextNode(''));

                    sellerOne.appendChild(optionOne);

                    let optionTwo = document.createElement('option');
                    optionTwo.setAttribute('value', 0);
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

                    if (res.decoded_referrer_id) {
                        sellerOne.value = res.decoded_referrer_id;
                        sellerOne.setAttribute("disabled", true);
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
                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateStates(option) {
            // text : option.options[option.selectedIndex].innerHTML,
            // value : option.value
            fetch('{{ url('') }}' + `/api/country/${option.value}/states`, {
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

                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }

        function populateCities(option) {
            fetch('{{ url('') }}' + `/api/state/${option.value}/cities`, {
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

                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }
    </script>
@endsection
