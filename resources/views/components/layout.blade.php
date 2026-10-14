<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>{{ config('app.name', 'Customer') }}</title>
    </head>
    <body>
        @include('components.navigation')
        @include('components.sidebar')
        {{$slot}}
    </body>
</html>