@extends('layouts.default')

@section('content')
<div class="container-fluid" style="margin-top:15px">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
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
                        <input type="hidden" name="redirect" value="{{ Request::input("redirect")==''?'dashboard':Request::input("redirect") }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Email</label>
                            <div class="col-md-6">
                                <input type="input" class="form-control" name="login" value="{{ old('login') }}">
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
                                <button type="submit" class="btn btn-primary pull-right">Login</button>
                                <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
                                
                            </div>
                            <div class="col-md-6 col-md-offset-4" style="padding-top:15px; text-align: center">
                                <hr>
                            </div>
                            <div class="col-md-12" style="padding-top:15px; text-align: center">
                                <a href="http://www.minidel.com/auth/facebook/" class="btn btn-info" style="background:#3b5998">Login with Facebook</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection