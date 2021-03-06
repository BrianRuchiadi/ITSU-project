<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ITSU Contract Management v{{ env('VERSION') }} </title>
        @yield('styles')

        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/app.css">

    </head>
    <body>
        @yield('header')
        @yield('content')
        @yield('footer')
        @yield('scripts')
    </body>

</html>
