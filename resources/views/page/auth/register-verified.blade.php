@extends('layout.basic')

@section('styles')    
    <link rel="stylesheet" type="text/css" href="/css/page/auth/verified.css">
@endsection

@section('content')
<div class="content">
    <div class="verified-panel">
        <form method="POST" action="{{ route('auth.register.verify') }}">
            {{ csrf_field() }}
            <h3 class="center">Click this button to complete account register</h3>
            <input type="hidden" class="form-control" placeholder="Hidden Id" name="id" value="{{ $id }}">
            @if(session()->has('message'))
                <div class="alert alert-danger">
                {{ session()->get('message') }}
                </div>
            @endif
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-block">Verify</button>
            </div>
        </form>
    </div>
    
</div>
@endsection