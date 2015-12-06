@extends('layouts.default')

@section('head')
<style type="text/css">
@media(max-width:767px){
        .indexFirst{padding:200px 0 10px 10px; font-size:1.2em;}
        #loginFormDiv{text-align:center; padding:50px 10px;}
        .indexText{font-size: 1.5em; font-family: lane; text-align: center;}
        .service-box h3{font-size:1em;}
        .bigIcon {font-size: 3em;}
        #locationsMap {height:250px; width:100%}

}
@media(min-width:768px){
        .indexFirst{padding:550px 0 10px 1em; font-size:2.75em;}
        #loginFormDiv{text-align:center; padding:80px 10px;}
        .indexText{font-size: 2em; font-family: lane; text-align: center;}
        .bigIcon {font-size: 4em;}
        #locationsMap {height:450px; width:90%;}
}

  .indexFirst{
      background: url('/img/deskcover.jpg') no-repeat center center; 
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
      color:white;
      font-weight: bold;
    }

    .reddish{color:#fe602c;}
    
    .bigIconSecond {font-size: 3em;}
</style>
@endsection


@section('content')

        <div class="row" style="margin-top:-20px;">
            <div class="col-xs-12 indexFirst">
                <div style="font-family: lane">Your coffee delivered with a tap!</div>
            </div>
        </div>





        <div class="row" style="padding:35px 10px;">
            <div class="col-xs-12">
                <div style="color:#fe602c; font-family: lane; text-align: center; font-size:3em">What is minidel?</div>
                <div class="indexText">Minidel (Mini + deliveries) is a ordering service that let you order from stores that are close to you with <b>No Delivery Charge</b> and <b>No Minimum Delivery Amount</b>. You can order anything as small as a cup of coffee or your grocories.</div>
            


 @if (\Auth::user())
    <div id="loginFormDiv">
        <a href="/dashboard/" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-home"></span> Go to Dashboard</a>
    </div>
@else
    
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div id="loginFormDiv">
    <form class="form-inline" role="form" method="POST" action="{{ url('/auth/login') }}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="redirect" value="dashboard">
      <div class="form-group">
        <label for="login">Login</label>
        <input type="text" class="form-control" name="login" id="login" placeholder="Email or Username" value="{{ old('login') }}">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" id="password">
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
      <a href="/auth/register/" class="btn btn-default">Register</a>
    </form>
    </div>
@endif
            <div class="col-lg-3 col-xs-6 text-center">
                <div class="service-box">
                    <i class="glyphicon glyphicon-apple reddish bigIcon"></i>
                    <h3>Easy To Use</h3>
                    <p class="text-muted">You will have live update about your order anytime.</p>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6 text-center">
                <div class="service-box">
                    <span class="glyphicon glyphicon-glass reddish bigIcon"></span>
                    <h3>No Minimum Order</h3>
                    <p class="text-muted">Order a cup of coffee from your nearby stores that offers you no minimum order!</p>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6 text-center">
                <div class="service-box">
                    <span class="glyphicon glyphicon-eur reddish bigIcon"></span>
                    <h3>No Delivery Charges</h3>
                    <p class="text-muted">Do not pay extra for delivery charges.</p>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6 text-center">
                <div class="service-box">
                    <span class="glyphicon glyphicon-heart reddish bigIcon"></span>
                    <h3>Made with Love</h3>
                    <p class="text-muted">We will take care of your orders with love!</p>
                </div>
            </div>





            </div>


        </div>

        <div class="row" style="background-color: #f05f40; padding: 40px 10px;">
            <div class="col-xs-12" style="">
                <div style="color:white; text-align: center; font-size:2em; padding:15px; font-family: lane;">Where can you order?</div>
                <div style="text-align: center; padding:10px;">
                        <iframe width="90%" id="locationsMap" frameBorder="0" src="http://umap.openstreetmap.fr/en/map/minidel-partners_59384?scaleControl=false&miniMap=false&scrollWheelZoom=false&zoomControl=true&allowEdit=false&moreControl=false&datalayersControl=false&onLoadPanel=none&captionBar=false"></iframe>
                        <br><br><br>
                </div>
            </div>
        </div>






        <div class="row" style="padding: 80px 10px; background-color: #F3F3F3; margin-bottom: -20px;">
            <div class="col-lg-8 col-lg-offset-2 text-center" style="margin-bottom:40px;">
                <h2 style=" text-align: center; font-size:2em">Let's Get In Touch!</h2>
                <hr class="primary">
                <p class="indexText">Have any questions or query? Give us a call or send us an email and we will get back to you as soon as possible!</p>
            </div>
            <div class="col-xs-6 col-sm-4 col-sm-offset-2 text-center">
                <i class="glyphicon glyphicon-earphone bigIconSecond"></i>
                <p><a href="tel:+971504567960" style="font-size: 1.5em; font-family: lane;">+971507687808</a></p>
            </div>
            <div class="col-xs-6 col-sm-4 text-center">
                <i class="glyphicon glyphicon-envelope bigIconSecond"></i>
                <p><a href="mailto:hello@minidel.com" style="font-size: 1.5em; font-family: lane;">hello@minidel.com</a></p>
            </div>
        </div>





@endsection




@section('footer')

@endsection