@extends('layouts.default')


@section('head')

<style type="text/css">

.firstPart{ margin-top: -20px; }

.firstPart .bg{
    background: url('http://www.ladigital.me/images/ladigital-jlt.jpg') no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  padding:3em 2em;
}

.firstPart .title{
    font-size:2em;
    color:black;
    padding:1em;
    background-color: #FFDC7B;
    text-align: center;
}


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
    #firstPageLogin{ }

}
@media(min-width:768px){
    #firstPageLogin{ width:300px; margin:0 auto; }
}
@media(min-width:992px){
    #firstPageLogin{ width:400px; margin:0 auto; }

}
@media(min-width:1200px){
    #firstPageLogin{ width:500px; margin:0 auto; }
}






</style>



@endsection


@section('content')


<div class="row firstPart">
    <div class="bg">
        



        @if (\Auth::user())

           <div class="panel panel-default" id="firstPageLogin">
                <div class="panel-heading">Welcome</div>
                <div class="panel-body">
                    Browse
                </div>
            </div>


        @else


            <div class="panel panel-default" id="firstPageLogin">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
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

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="redirect" value="dashboard">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Email</label>
                            <div class="col-md-6">
                                <input type="input" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-default">Register</button>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="submit" class="btn btn-primary">Login</button>

                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        @endif












    </div>
    <div class="title">
        <h1>Room service for your home!</h1>
        <h2>Order from your surrounding stores with no minimum delivery & no delivery charges!</h2>
        <h3>Currenly available in JLT Dubai only</h3>
    </div>


</div>

































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



@endsection




@section('footer')

@endsection