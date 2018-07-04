<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ (isset($page_title))?$page_title:'Minidel.com | Order delivery from your nearby stores' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/all.css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield('head')
</head>
<body @yield('bodyProp')>

    @yield('header')

    @include('layouts.partials.nav')

    @yield('breadcrumb')

    <div class="{{ (isset($body_class))?$body_class:'container'}}" id="defaultMainContainer">
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
        // window.onbeforeunload = function(){ 
        //     $("#fakeloaderExit").fakeLoader({
        //                 timeToHide:1000,
        //                 bgColor: "rgba(250, 165, 20, 0.3)",
        //                 spinner:"spinner5"
        //     });
        // }

        $(document).ready(function(){

            // $("#fakeloaderEnter").fakeLoader({
            //             timeToHide:1000,
            //             bgColor: "rgba(250, 165, 20, 0.3)",
            //             spinner:"spinner5"
            // });

            $("#globalSearch").on("show.bs.collapse shown.bs.collapse",function(event){
                $("#globalSearch input").focus();
            });


            
        });





    </script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-72963025-1', 'auto');
      ga('send', 'pageview');

    </script>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5b3c91bd6d961556373d64e5/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
    
    @yield('footer')

  <?php // echo(json_encode(DB::getQueryLog())); ?>
  
</body>
</html>