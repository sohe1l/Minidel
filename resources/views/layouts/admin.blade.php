<!doctype html>
<html lang="en" ng-app="larabook">
<head>
    <meta charset="UTF-8">
    <title>Pand.io</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/css/app.css">
    @yield('head')
</head>
<body>

    @yield('header')

    @include('layouts.partials.nav')


    @include('flash::message')

    <div class="container">

        @yield('content')
    </div>

    @yield('footer')

    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>