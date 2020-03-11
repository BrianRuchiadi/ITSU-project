@extends('layout.basic')

@section('styles')
    <link rel="stylesheet" type="text/css" href="css/page/register.css">
@endsection

@section('content')
<div class="content">
    <div class="login-panel">
        <h2 class="center">
            <i class="fas fa-chevron-left force-left" onclick="backToLogin()"></i>
            <i class="fas fa-user-plus"></i>Register
        </h2>

        <h3 class="center">Register for Individual</h3>
        <form method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-address-card"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Enter Your IC No / Passport No" name="ic_no">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-mobile-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Mobile No" name="mobile_no">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                      </span>
                    </div>
                    <input type="email" class="form-control" placeholder="Email Address" name="email_address">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-user"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="User Id" name="user_id">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" placeholder="Password" name="password">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
                </div>
            </div>
            <div class="form-group">
                <input type="checkbox" id="tandc" name="tandc" value="1">
                <label for="tandc">I have read & agree to the <a class="tandc">Terms & Condition</a></label><br>
            </div>
            
            <div class="container button">
                <div class="row">
                  <div class="col-sm">
                    <button type="submit" class="btn btn-success">
                        Register
                    </button>
                  </div>
                  <div class="col-sm">
                    <button type="button" class="btn btn-danger">
                        Cancel
                    </button>
                  </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        <li>{{$errors->first()}} </li>
                    </ul>
                </div>
            @endif
        </form>
    </div>
    
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    function backToLogin(){
        window.location = "{{ url('login') }}";
    }
</script>
@endsection