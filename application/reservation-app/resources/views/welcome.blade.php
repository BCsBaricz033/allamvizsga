<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        @vite('resources/js/welcome.js','resources/css/welcome.css')
    </head>
    <body>
        <div id="welcome">
        <router-view></router-view>
        </div>
    </body>
</html>
