<!doctype html>
<html lang="en" ng-app="larabook">
<head>
    <meta charset="UTF-8">
    <title>Pand.io</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/all.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
    @yield('head')
</head>
<body>

    @yield('header')

    @include('layouts.partials.nav')


    

    <div class="container" id="defaultMainContainer">
        @include('flash::message')

        @yield('content')
    </div>






















<style>
.footerLinks {
    background-color: #F5F5F5;
    text-align: center;
    padding:1em 0;
}
.footerLinks ul { 
    padding: 0;
    margin: 0;
    list-style-type: none;
 }

.footerRow{
    background-color: #FAFAFA;
    border-top:1px solid #F0F0F0;
    padding:10px;
    font-size: 80%
}


@media(max-width:767px){

}
@media(min-width:768px){
}
@media(min-width:992px){

}
@media(min-width:1200px){
    #defaultMainContainer {min-height: 500px;}
}




</style>


    <div class="container-fluid">

<div class="row footerLinks">
  <div class="col-md-3">
    <h5>Resturant Owners</h5>
    <ul>
        <li>FAQs</li>
        <li>How to use?</li>
        <li><a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a></li>
    </ul>
  </div>


  <div class="col-md-3">
    <h5>Resturant Owners</h5>
    <ul>
        <li>How does it work</li>
        
    </ul>
  </div>

  <div class="col-md-3">
    <h5>Social</h5>
    <ul>
        <li>Facebook</li>
        <li>Instagram</li>
        <li>Twitter</li>
    </ul>
  </div>

  <div class="col-md-3">
    <h5>About</h5>
    <ul>
        <li>About Minidelivery</li>
        <li>Privacy</li>
        <li>Terms of Service</li>

        <li>Contact Us</li>
    </ul>     

  </div>
</div>
<div class="row footerRow">
All Rights Reserved.
</div>

</div>



    <script src="//code.jquery.com/jquery.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/0.12.14/vue.min.js"></script>

    <script src="/js/all.js"></script>
    

    @yield('footer')
</body>
</html>