@extends('layout.basic')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/auth/register.css">
@endsection

@section('content')
<div class="content">
    <div class="login-panel">
        <h2 class="center">
            <i class="fas fa-chevron-left force-left" onclick="backToLogin()"></i>
            <i class="fas fa-user-plus"></i>Register
        </h2>

        <h3 class="center">Register for Individual</h3>
        <form class="form-horizontal" action="{{ route('auth.register') }}" method="POST" enctype="multipart/form-data">
					    {{ csrf_field() }}
            <div class="form-group{{ $errors->has('email_address') ? ' has-error' : '' }}">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                      </span>
                    </div>
                    <input type="email" class="form-control" placeholder="Email Address" name="email_address" value="{{ old('email_address') }}" required autofocus>
                </div>
                @error('email_address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
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
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
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
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
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
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
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
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input type="checkbox" id="tandc" name="tandc" value="1" required>
                <label for="tandc">I have read & agree to the <a href="https://www.google.com" target="blank" class="tandc">Terms & Condition</a></label>
                @error('tandc')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
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
                    <button type="button" class="btn btn-danger">
                        Cancel
                    </button>
                  </div>
                </div>
            </div>
            @if(session()->has('message'))
              <div class="alert alert-danger">
              {{ session()->get('message') }}
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