@extends('layout.basic')

@section('styles')
    <link rel="stylesheet" type="text/css" href="css/page/login.css">
@endsection

@section('content')
<div class="content">
    <div class="login-panel">
        <h2><i class="fas fa-sign-in-alt"></i> Login</h2>
        <form method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" class="form-control" placeholder="User Id" name="user_id">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-block">Login</button>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-secondary btn-block">Register Now</button>
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