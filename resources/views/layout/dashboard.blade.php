<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ITSU Contract Management</title>
        @yield('styles')

        <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> -->

    </head>
    <body>
        DASHY DASHY
        @yield('header')
        @yield('content')
        @yield('footer')
        @yield('scripts')
        <script type="text/javascript">
            const input = document.getElementById("input-copy");
            const button = document.getElementById("button-copy");
            button.onclick = function () {
                input.select();
                document.execCommand('Copy');
            }
        </script>
    </body>

</html>
