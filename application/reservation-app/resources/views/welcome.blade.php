<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>
        @vite('resources/js/welcome.js','resources/css/welcome.css')
    </head>
    <body>
        @include('cookie-consent::index')
        <div id="welcome">
        <router-view></router-view>
        </div>
        <script>
</script>
    </body>
</html>
