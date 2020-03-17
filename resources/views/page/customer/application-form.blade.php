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
                    <select class="form-control" id="item-options" name="product">
                    </select>
                </div>
            </div>
      
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">No Of Installment Month</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="No Of Installment Month" name="no_of_installment_month">
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
                  <input type="text" class="form-control" placeholder="Name Of Applicant" id="name-of-applicant" name="name_of_applicant">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">IC Number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="IC Number" name="ic_number">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Contact 1 Of Applicant</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="6012-333-4444">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Contact 1 SMS tag</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="SMS tag">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Contact 2 Of Applicant</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="6015-666-7777">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Email Of Applicant</label>
                <div class="col-sm-8">
                  <input type="email" class="form-control" placeholder="jane.doe@gmail.com">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Address 1</label>
                <div class="col-sm-8">
                    <textarea class="form-control" placeholder="Address 1">
                    </textarea>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Address 2</label>
                <div class="col-sm-8">
                    <textarea class="form-control" placeholder="Address 1">
                    </textarea>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Postcode</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Postcode">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Country</label>
                <div class="col-sm-8">
                    <select class="form-control" id="country-options" onchange="populateStates(this)">
                    </select>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">State</label>
                <div class="col-sm-8">
                    <select class="form-control" id="state-options" onchange="populateCities(this)">
                    </select>
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">City</label>
                <div class="col-sm-8">
                    <select class="form-control" id="city-options">

                    </select>
                </div>
            </div>
        </section>

        <h3 class="section-header" onclick="toggleRequirements('referral-information')">
            3. Referral Information
            <i class="fas fa-caret-down right"></i>
        </h3>
        <section class="group referral-information">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Name Of Reference</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Name Of Reference">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Contact of Reference</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Contact Of Reference">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Seller 1</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Seller 1">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Seller 2</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Seller 2">
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


        <div class="form-group row">
            <div class="col-sm-9">
                <div>
                    <input type="checkbox" id="tandcitsu" value="1" required>
                    <label for="tandc">I have read & agree to the <a href="https://www.google.com" target="blank" class="tandc">Terms & Condition</a></label>
                    @error('tandc')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <input type="checkbox" id="tandcctos" value="1" required>
                    <label for="tandc">I have read & agree with <a href="https://www.google.com" target="blank" class="tandc">CTOS Consent Authorization</a></label>
                    @error('tandc')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
            </div>
            <div class="col-sm-3" style="margin: auto;">
                <button class="btn btn-primary btn-block" type="submit">Submit</button>
            </div>
            
        </div>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript">
        let countryOptions = document.getElementById('country-options');
        let stateOptions = document.getElementById('state-options');
        let cityOptions = document.getElementById('city-options');
        
        let itemOptions = document.getElementById('item-options');
        let nameOfApplicant = document.getElementById('name-of-applicant');
        let applicantName = '{{ Auth::user()->branchind === 4 ? Auth::user()->name : '' }}';

        this.getCountryOptions();
        this.getItems();
        this.fillApplicantName();

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
