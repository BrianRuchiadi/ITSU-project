@extends('layout.basic')

@section('styles')    
    <link rel="stylesheet" type="text/css" href="/css/page/auth/verified.css">
@endsection

@section('content')
<div class="content">
    <div class="verified-panel">
        <form method="POST" action="{{ route('auth.reset.verify') }}">
            {{ csrf_field() }}
            <h3>Reset Password</h3>
            <input type="hidden" placeholder="ID" name="id" value="{{ $id }}">
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" placeholder="New Password" name="new_password" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-key"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control" placeholder="Confirm Password" name="new_password_confirmation" required>
                </div>
            </div>@if(session()->has('message'))
                <div class="alert alert-danger">
                {{ session()->get('message') }}
                </div>
            @endif
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-block">Confirm</button>
            </div>
        </form>
    </div>
    
</div>
@endsection