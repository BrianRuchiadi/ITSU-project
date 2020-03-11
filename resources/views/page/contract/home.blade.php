@extends('layout.dashboard')

@section('styles')

@endsection

@section('content')

<div>
    <!-- Target -->
    <input id="input-copy" value="https://github.com/zenorocha/clipboard.js.git">

    <!-- Trigger -->
    <button class="btn" id="button-copy">
        <img src="assets/clippy.svg" alt="Copy to clipboard">
    </button>
</div>

@endsection