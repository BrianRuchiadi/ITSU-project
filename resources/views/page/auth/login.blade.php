@extends('layout.basic')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/auth/login.css">
@endsection

@section('content')
<div class="content">
    <div class="login-panel">
        <h2><i class="fas fa-sign-in-alt"></i> Login</h2>
        <form method="POST">
            {{ csrf_field() }}
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

            @if ($errors->any())
            <div class="error">
                * {{$errors->first()}}
            </div>
            @endif

            @if (session()->has('flash_notification.message'))
                <div class="container">
                    <div class="alert alert-{{ session()->get('flash_notification.level') }}">
                       * {!! session()->get('flash_notification.message') !!}
                    </div>
                </div>
            @endif
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-block">Login</button>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-secondary btn-block register">
                    <a href="{{ url('/register') }}">Register Now</a>
                </button>
            </div>
        </form>
    </div>
    
</div>
@endsection