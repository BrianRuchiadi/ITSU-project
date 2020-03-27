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
            <nav class="side-bar show" id="user-sidemenu">
                <div class="logo">
                    <h2 class="center">ITSU</h2>
                </div>
                <ul>
                    @if(Auth::user()->acc_customer_module)
                    <li onclick="toggleNavSubMenu('customer')">
                        <i class="fas fa-users indicator"></i>
                        Customer
                        <i class="fas fa-caret-down expander"></i>
                    </li>
                    @endif

                        @if(Auth::user()->branchind === 0)
                            <li class="customer">
                                <a href="{{ url('/apply') }}">
                                    Application Form
                                </a>
                            </li>
                            <li class="customer">
                                <a href="{{ url('/contract') }}">
                                    Contract List
                                </a>
                            </li>
                            <li class="customer">
                                <a href="{{ url('/link/referral')}}">
                                    Referral Link
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->branchind === 4)
                            <li class="customer">
                                <a href="{{ url('/apply') }}">
                                    Application Form
                                </a>
                            </li>
                            <li class="customer">
                                <a href="{{ url('/contract') }}">
                                    Contract List
                                </a>
                            </li>
                        @endif


                    @if(Auth::user()->acc_contract_module)
                    <li onclick="toggleNavSubMenu('contract')">
                        <i class="fas fa-handshake indicator"></i>
                        Contract
                        <i class="fas fa-caret-down expander"></i>
                    </li>
                    @endif

                    @if(Auth::user()->branchind === 0)
                        <li class="contract">
                            <a href="{{ url('/pending-contract') }}">
                                Pending Contract
                            </a>
                        </li>
                        <li class="contract">
                            <a href="{{ url('/delivery-order') }}">
                                Delivery Order
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

            <div class="main-content" id="main-content">
                <header>
                    <i class="fas fa-bars burger" onclick="displayUserSideMenu()"></i>
                    <span class="user-management" onclick="displayUserSubMenu()">
                        <h4>
                            <i class="fas fa-user"></i>
                            {{ Auth::user()->userid }}
                            <i class="fas fa-caret-down"></i>
                        </h4>
                    </span>
                    <ul class="user-submenu" id="user-submenu">
                        <li>
                            <a href="{{ url('/change-password')}}">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                        </li>
                        <li>
                            <a onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </header>
            
                <div class="content">
                    @yield('content')
                </div>
            </div>

            <div class="alert" id="alert">
                Successfully copied the text
            </div>
        </div>
        @yield('header')
        @yield('footer')
        @yield('scripts')
        <script type="text/javascript">
            const subMenus = ['customer', 'contract'];

            let elementUserSubmenu = document.getElementById('user-submenu');
            let elementUserSidemenu = document.getElementById('user-sidemenu');
            let elementMainContent = document.getElementById('main-content');
            let elementAlert = document.getElementById('alert');
            let showSubmenu = false;
            let showSidemenu = true;

            @if (Session::has('showSuccessMessage'))
                this.showAlert('{{ Session::get('showSuccessMessage') }}');
            @endif
        
            function displayUserSubMenu() {
                elementUserSubmenu.classList.toggle("show");

                showSubmenu = !showSubmenu;
            }

            function showAlert(message, alertType = 'alert-success') {
                elementAlert.classList.add(alertType);
                elementAlert.classList.add('show');
                elementAlert.innerText = message;

                setTimeout(function () {
                    elementAlert.classList.remove(alertType);
                    elementAlert.classList.remove('show');
                }, 3000);
            }

            function displayUserSideMenu() {
                elementUserSidemenu.classList.toggle("show");
                elementMainContent.classList.toggle("expand");

                showSidemenu = !showSidemenu;
            }

            function toggleNavSubMenu(option) {
                let lists = document.getElementsByClassName(option);

                this.closeNavSubMenu();

                for (let i = 0; i < lists.length; i++) {
                    lists[i].classList.add("show");
                }
            }

            function closeNavSubMenu() {
                for (let menu of subMenus) {
                    const lists = document.getElementsByClassName(menu);

                    for (let i = 0; i < lists.length; i++) {
                        lists[i].classList.remove("show");
                    }
                }
            }

            function logout() {
                fetch("{{ url('/logout') }}", {
                    method: 'POST', // or 'PUT'
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                    })
                    .then((response) => {
                        if (response.redirected) { window.location = response.url; }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                });
            }

            // const input = document.getElementById("input-copy");
            // const button = document.getElementById("button-copy");
            // button.onclick = function () {
            //     input.select();
            //     document.execCommand('Copy');
            // }
        </script>
    </body>

</html>
