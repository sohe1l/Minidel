<!doctype html>
<html lang="en" ng-app="larabook">
<head>
    <meta charset="UTF-8">
    <title>minidel.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/all.css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield('head')
</head>
<body>

    @yield('header')

    @include('layouts.partials.nav')



    <div class="container" id="defaultMainContainer">
        @include('flash::message')

        @yield('content')
    </div>


    @include('layouts.partials.footer')

    <script src="/js/all.js"></script>

    @yield('footer')
</body>
</html>