@extends('layout.basic')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/auth/register.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
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
                    <select class="js-example-basic-single form-control d-inline col-sm-5" id="tel-code-options" name="tel_code" required></select>
                    <input type="text" class="form-control d-inline" name="telephoneno" placeholder="123456789" required>
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

            @if (Session::get('message'))
            <div class="form-group">
                <div class="alert alert-danger">
                    <strong>{{ Session::get('message') }}</strong>
                </div>
            </div>
            @endif
            
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
<script type="text/javascript" src="/js/vendor/vendor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });

    this.getCountryTelCode();

    function backToLogin() {
        window.location = "{{ url('login') }}";
    }

    function formReset() {
        document.getElementById('register-form').reset();
    }
    
    function getCountryTelCode() {
            fetch("{{ url('/api/country/tel-code') }}", {
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
                    
                    let telCodeOptions1 = document.getElementById('tel-code-options');
                    let option = document.createElement('option');
                    option.setAttribute('value', '');
                    option.appendChild(document.createTextNode('-- Tel Code --'));

                    telCodeOptions1.appendChild(option);
                    for (let each of res.data) {
                        let option = document.createElement('option');
                        option.setAttribute('value', each.dial_code);
                        option.appendChild(document.createTextNode(`${each.name} (${each.dial_code})`));

                        telCodeOptions1.appendChild(option);
                    }
                })
                .catch((error) => {
                    console.log(['err', err]);
                });
        }
        
</script>
@endsection