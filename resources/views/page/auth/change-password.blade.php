@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/auth/change-password.css">
@endsection

@section('content')
    <h2 class="center">Change Password</h2>
    <form class="form-horizontal" action="{{ route('auth.change.password') }}" method="POST" enctype="multipart/form-data" id="change-password-form">
        {{ csrf_field() }}

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Current Password</label>
            <div class="col-sm-10">
            <input type="password" class="form-control" placeholder="Current Password" name="current_password" required>
            </div>
                @error('current_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">New Password</label>
            <div class="col-sm-10">
            <input type="password" class="form-control" placeholder="New Password" name="new_password" required>
            </div>
                @error('new_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">New Password Confirmation</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" placeholder="New Password Confirmation" name="new_password_conf" required>
            </div>
                @error('new_password_conf')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
        <div class="button">
            <div class="row">
                <div class="col-sm">
                <button type="submit" class="btn btn-success">
                    Change Password
                </button>
                    @if(session()->has('message'))
                        <span class="alert-danger">
                            {{ session()->get('message') }}
                        </span>    
                    @endif
                </div>
            </div>
        </div>
       

    </form>
@endsection
