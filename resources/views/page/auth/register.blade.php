@extends('layout.basic')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/auth/register.css">
@endsection

@section('content')
<div class="content">
    <div class="register-panel">
        <h2 class="center">
            <i class="fas fa-chevron-left force-left btn" onclick="backToLogin()"></i>
            <i class="fas fa-user-plus"></i>Register
        </h2>

        <h3 class="center">Register for Individual</h3>
        <form class="form-horizontal" action="{{ route('auth.register') }}" method="POST" enctype="multipart/form-data" id="register-form">
					    {{ csrf_field() }}
            <div class="form-group{{ $errors->has('telephoneno') ? ' has-error' : '' }}">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-phone"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="+60123456789" name="telephoneno" value="{{ old('telephoneno') }}" required autofocus>
                </div>
                @error('telephoneno')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            <div class="form-group{{ $errors->has('email_address') ? ' has-error' : '' }}">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                      </span>
                    </div>
                    <input type="email" class="form-control" placeholder="Email Address" name="email_address" value="{{ old('email_address') }}" required>
                </div>
                @error('email_address')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-user"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="User Id" name="user_id" value="{{ old('user_id') }}"  required>
                </div>
                @error('user_id')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-user"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Name" name="name" value="{{ old('name') }}"  required>
                </div>
                @error('name')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" placeholder="Password" name="password" value="{{ old('password') }}"  required>
                </div>
                @error('password')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{ old('password_confirmation') }}"  required>
                </div>
                @error('password')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <input type="checkbox" id="tandc" name="tandc" value="1" required>
                <label for="tandc">I have read & agree to the <a href="https://www.google.com" target="blank" class="tandc">Terms & Condition</a></label>
                @error('tandc')
                    <div class="alert alert-danger">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>
            
            <div class="container button">
                <div class="row">
                  <div class="col-sm">
                    <button type="submit" class="btn btn-success">
                        Register
                    </button>
                  </div>
                  <div class="col-sm">
                    <button type="button" class="btn btn-danger" onclick="formReset()">
                        Clear
                    </button>
                  </div>
                </div>
            </div>
        </form>
    </div>
    
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    const queryStrings = this.getAllQueryString(window.location.search);
    const referrerCode = this.getReferrerCode();

    this.injectReferrerCode();

    function backToLogin() {
        window.location = "{{ url('login') }}";
    }

    function getAllQueryString(url) {
        let queryParams = {};
        //create an anchor tag to use the property called search
        let anchor = document.createElement('a');
        //assigning url to href of anchor tag
        anchor.href = url;
        //search property returns the query string of url
        let queryStrings = anchor.search.substring(1);
        let params = queryStrings.split('&');

        for (var i = 0; i < params.length; i++) {
            var pair = params[i].split('=');
            queryParams[pair[0]] = decodeURIComponent(pair[1]);
        }
        return queryParams;
    };

    function getReferrerCode() {
        const referrerCode = (queryStrings['ref']) ? queryStrings['ref'] : localStorage.getItem("referrerCode");
        return referrerCode;
    }

    function injectReferrerCode() {
        if (!referrerCode) { return; }
        localStorage.setItem("referrerCode", referrerCode);

        let input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "referrer_code");
        input.setAttribute("value", referrerCode);

        //append to form element that you want .
        document.getElementById("register-form").appendChild(input);
    }

    function formReset() {
        document.getElementById('register-form').reset();
    }
</script>
@endsection