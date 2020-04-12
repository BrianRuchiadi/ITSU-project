<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ITSU Contract Management v{{ env('VERSION') }}</title>
        @yield('styles')

        <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> -->
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/layout/dashboard.css">
    </head>
    <body>
        <div class="container">
            <header>
                <div class="side-nav">
                    <input type="checkbox" class="toggler" onchange="toggleContentPanel(this)">
                    <h1 class="title">ITSU</h1>


                    <div class="burger-wrapper">
                        <div class="burger"></div>
                    </div>

                    <ul class="main">
                        @if(Auth::user()->acc_customer_module)
                            <li class="customer-master">
                                <div class="li-header">
                                    <i class="fas fa-users indicator"></i>
                                    Customer
                                    <i class="fas fa-caret-down expander"></i>
                                </div>
                                <input type="checkbox" class="customer-toggler">
                                <ul class="inside">
                                    @if(Auth::user()->branchind === 0)
                                    <li class="customer">
                                        <a href="{{ url('/customer/apply') }}">
                                            Application Form
                                        </a>
                                    </li>
                                    <li class="customer">
                                        <a href="{{ url('/customer/contract') }}">
                                            Contract List
                                        </a>
                                    </li>
                                    <li class="customer">
                                        <a href="{{ url('/customer/link/referral')}}">
                                            Referral Link
                                        </a>
                                    </li>
                                    @endif
            
                                    @if(Auth::user()->branchind === 4)
                                        <li class="customer">
                                            <a href="{{ url('/customer/apply') }}">
                                                Application Form
                                            </a>
                                        </li>
                                        <li class="customer">
                                            <a href="{{ url('/customer/contract') }}">
                                                Contract List
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif



                        @if(Auth::user()->acc_contract_module)
                            <li class="contract-master">
                                <div class="li-header">
                                    <i class="fas fa-handshake indicator"></i>
                                    Contract
                                    <i class="fas fa-caret-down expander"></i>
                                </div>
                                <input type="checkbox" class="contract-toggler">
                                <ul class="inside">
                                    <li class="contract">
                                        <a href="{{ url('/contract/invoices') }}">
                                            Invoice List
                                        </a>
                                    </li>
                                    <li class="contract">
                                        <a href="{{ url('/contract/pending-contract') }}">
                                            Pending Contract
                                        </a>
                                    </li>
                                    <li class="contract">
                                        <a href="{{ url('/contract/delivery-order') }}">
                                            Delivery Order
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        @endif
                    </ul>
                </div>
                <div class="account-submenu">
                    <div>
                        <i class="fas fa-user-alt"></i>
                        {{ Auth::user()->userid }}
                    </div>
                    <input type="checkbox" class="submenu-toggler">

                    <ul class="submenu-option">
                        <li>
                            <a href="{{ url('change-password') }}">
                                <i class="fas fa-key"></i>Change Password
                            </a>
                        </li>
                        <li>
                            <a onclick="performLogout()">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </header>
            <div class="content-container" id="content-container">
                @yield('content')
            </div>

            <div class="alert" id="alert">
            </div>
        </div>
        
        @yield('header')
        @yield('footer')
        <script type="text/javascript">

            @if (Auth::user()->branchind == 0)
                localStorage.removeItem('referrerCode');
            @endif

            let contentContainer = document.getElementById('content-container');
            let elementAlert = document.getElementById('alert');

            @if (Session::has('showSuccessMessage'))
                this.showAlert('{{ Session::get('showSuccessMessage') }}');
            @endif
            function toggleContentPanel(obj) {
                if (obj.checked) {
                    contentContainer.classList.add('compressed');
                } else {
                    contentContainer.classList.remove('compressed');
                }
            }

            function showAlert(message, alertType = 'alert-success') {
                elementAlert.classList.add(alertType);
                elementAlert.classList.add('show');

                if (Array.isArray(message)) {
                    // process with array
                } else {
                    elementAlert.innerHTML = message;
                }

                setTimeout(function () {
                    elementAlert.classList.remove(alertType);
                    elementAlert.classList.remove('show');
                }, 3000);
            }

            function performLogout() {
                fetch("{{ url('/logout') }}", {
                    method: 'POST', // or 'PUT'
                    headers: {
                        'Content-Type': 'application/json',
                        // 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                    })
                    .then((response) => {
                        localStorage.removeItem('referrerCode');
                        if (response.redirected) { window.location = response.url; }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                });
            }
        </script>
        @yield('scripts')
    </body>

</html>
