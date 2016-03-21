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
<body style="padding-top:10px !important;" @yield('bodyProp')>

    @yield('header')

    <div class="{{ (isset($body_class))?$body_class:'container'}}" id="defaultMainContainer">
        @include('flash::message')

        @yield('content')
    </div>




    <script src="/js/all.js"></script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-72963025-1', 'auto');
      ga('send', 'pageview');

    </script>
    
    @yield('footer')

  
</body>
</html>