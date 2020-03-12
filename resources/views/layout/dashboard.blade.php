<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ITSU Contract Management</title>
        @yield('styles')

        <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> -->
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/layout/dashboard.css">
    </head>
    <body>
        <div class="container">
            <nav class="side-bar">
                <div class="logo">
                    <h2 class="center">ITSU</h2>
                </div>
            </nav>

            <div class="main-content">
                <header>
                    <span class="user-management" onclick="displayUserSubMenu()">
                        <h4>
                            <i class="fas fa-user"></i>
                            {{ Auth::user()->userid }}
                            <i class="fas fa-caret-down"></i>
                        </h4>
                    </span>
                    <ul class="user-submenu" id="user-submenu">
                        <li>
                            <a href="#">
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
                

                <div class="content"></content>
            </div>
        </div>
        @yield('header')
        @yield('content')
        @yield('footer')
        @yield('scripts')
        <script type="text/javascript">
            let elementUserSubmenu = document.getElementById('user-submenu');
            let showSubmenu = false;
        
            function displayUserSubMenu() {
                if (!showSubmenu) {
                    elementUserSubmenu.classList.add("show");
                } else {
                    elementUserSubmenu.classList.remove("show");
                }

                showSubmenu = !showSubmenu;
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
