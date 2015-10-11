@extends('layouts.default')

@section('head')
@endsection


@section('content')


<div class="row firstPart">
    <div class="bg">
        



        @if (\Auth::user())

           <div class="panel panel-default" id="firstPageLogin" style="margin-top:4em; margin-bottom:4em;">
                <div class="panel-body">
                    <div style="font-size:2em; margin-bottom: 10px;">Welcome {{ \Auth::user()->name }}!</div>
                    
                    <br>
                    <div style="text-align: center">
                        <a href="/dashboard/" class="btn btn-lg btn-default"><span class="glyphicon glyphicon-home"></span> Go to Dashboard</a>
                    </div>
                    <br>
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
                                <a href="/auth/register/" class="btn btn-default">Register</a>
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
        <div id="firstTitle">
            <span>MINI = </span>
            <span>Your Personal Room Service</span>
        </div>

        <div id="listIndex">
            <ul>
                <li>No Minimum Order</li>
                <li>No Delivery Charge</li>
                <li>Only Surrunding Shops</li>
            </ul>
        </div>

        <div>Order from your surrounding stores with no minimum delivery & no delivery charges! <br>
        Beta Testing. Currenly available in Dubai, JLT</div>
    </div>


</div>




@endsection




@section('footer')

@endsection