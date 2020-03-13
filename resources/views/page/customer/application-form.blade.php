@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/customer/application-form.css">
@endsection

@section('content')
    <h2 class="center">Application Form</h2>
    <form>
        <h3 class="section-header">
            1. Product Installment
            <i class="fas fa-caret-down right"></i>
        </h3>
        <section class="group">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Product</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Product">
                </div>
            </div>
      
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">No Of Installment Month</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="No Of Installment Month">
                </div>
            </div>
        </section>
        
        <h3 class="section-header">
            2. Personal Information
            <i class="fas fa-caret-down right"></i>
        </h3>
        <section class="group">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Name Of Applicant</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Name Of Applicant">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">IC Number</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="IC Number">
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
                <label class="col-sm-4 col-form-label">City</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="City">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">State</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="State">
                </div>
            </div>
    
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Country</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Country">
                </div>
            </div>
        </section>

        <h3 class="section-header">
            3. Referral Information
            <i class="fas fa-caret-down right"></i>
        </h3>
        <section class="group">
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
                    <input type="checkbox" id="tandc" value="1" required>
                    <label for="tandc">I have read & agree to the <a href="https://www.google.com" target="blank" class="tandc">Terms & Condition</a></label>
                    @error('tandc')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <input type="checkbox" id="tandc" value="1" required>
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
        function toggleRequirements(className) {
            let elements = document.getElementsByClassName(className);

            for (el of elements) {
                el.classList.toggle("hide");
            }
        }
    </script>
@endsection
