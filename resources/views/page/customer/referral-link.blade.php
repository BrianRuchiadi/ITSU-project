@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/customer/referral-link.css">
@endsection

@section('content')
<div class="input-group mb-3">
    <input type="text" class="form-control" value="{{ env('APP_URL') }}/register" placeholder="referral link" id="referralLink">
    <div class="input-group-append" onclick="copyTextToClipboard('referralLink')">
      <span class="input-group-text">
        <i class="fas fa-clipboard"></i>
      </span>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        let elementReferralLink = document.getElementById('referralLink');
        this.getReferralLink();

        function copyTextToClipboard(elementId) {
            let elid = document.getElementById(elementId);
            elid.select();
            document.execCommand('Copy');
            
            showAlert("Successfully copied the text to clipboard");
        }

        function getReferralLink() {
            fetch("{{ url('/customer/api/link/referral') }}", {
                method: 'GET', // or 'PUT'
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
                })
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    elementReferralLink.value = data.url;
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
