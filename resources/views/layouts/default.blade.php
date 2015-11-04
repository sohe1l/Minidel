<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ (isset($page_title))?$page_title:'Minidel.com | order delivery from your nearby stores' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/all.css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield('head')
</head>
<body>

    @yield('header')

    @include('layouts.partials.nav')

    @yield('breadcrumb')

    <div class="container" id="defaultMainContainer">
        @include('flash::message')

        @yield('content')
    </div>


    @include('layouts.partials.footer')

    <div class="visible-xs">
        <div id="fakeloaderEnter"></div>
        <div id="fakeloaderExit"></div>
    </div>

    <script src="/js/all.js"></script>
    <script type="text/javascript">
        window.onbeforeunload = function(){ 
            $("#fakeloaderExit").fakeLoader({
                        timeToHide:1000,
                        bgColor: "rgba(250, 165, 20, 1)",
                        spinner:"spinner5"
            });
        }

        $(document).ready(function(){

            $("#fakeloaderEnter").fakeLoader({
                        timeToHide:1000,
                        bgColor: "rgba(250, 165, 20, 1)",
                        spinner:"spinner5"
            });


            
        });

    </script>

    @yield('footer')

  <?php // echo(json_encode(DB::getQueryLog())); ?>
  
</body>
</html>