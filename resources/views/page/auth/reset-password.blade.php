@extends('layout.basic')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/auth/reset-password.css">
@endsection

@section('content')
<div class="content">
    <div class="reset-password-panel">
        <h2>
            <i class="fas fa-chevron-left force-left btn" onclick="backToLogin()"></i>
            Reset Password
        </h2>
        <form class="form-horizontal" action="{{ route('auth.reset-password') }}" method="POST" enctype="multipart/form-data" id="reset-password-form">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-user"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="User Id" name="user_id" required>
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
                <button type="submit" class="btn btn-success btn-block">Send Email</button>
            </div>
        </form>
    </div>
    
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    function backToLogin() {
        window.location = "{{ url('login') }}";
    }
</script>
@endsection