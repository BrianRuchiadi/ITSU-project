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
                    <input type="text" class="form-control" placeholder="User Id" name="user_id" autofocus>
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
            <div class="form-group">
                <button type="button" class="btn btn-warning btn-block register">
                    <a href="{{ url('/reset-password') }}">Forgot Password</a>
                </button>
            </div>
            <div class="form-group">
        </form>
    </div>
    
</div>
@endsection
@section('scripts')
<script type="text/javascript">

    const queryStrings = this.getAllQueryString(window.location.search);
    const referrerCode = this.getReferrerCode();

    this.injectReferrerCode();

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
    }
</script>
@endsection