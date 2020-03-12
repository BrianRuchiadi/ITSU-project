@extends('layout.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="/css/page/auth/login.css">
@endsection

@section('content')
<div class="input-group mb-3">
    <input type="text" class="form-control" value="https://www.local.itsu.com/register" placeholder="referral link" id="referralLink">
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
            
            elementAlert.classList.add("show");
            elementAlert.classList.add("alert-success");
            elementAlert.value = "Successfully copied the text to clipboard";

            setTimeout(() => {
                elementAlert.classList.remove("show");
                elementAlert.classList.remove("alert-success");
                elementAlert.value = "";
            }, 3000)
        }

        function getReferralLink() {
            fetch("{{ url('/api/link/referral') }}", {
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
