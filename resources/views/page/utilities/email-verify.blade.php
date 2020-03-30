@extends('layout.basic')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/utilities/email-verify.css">
@endsection


@section('content')
<div class="content">
    <div class="email-verify-panel">
        <form method="POST" action="{{ route('contract.email.verify') }}">
            {{ csrf_field() }}
            <h3 class="center">Click this button to verify contract application email</h3>
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