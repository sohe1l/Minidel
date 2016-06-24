@extends('layouts.default')

@section('content')
<div class="container-fluid" style="margin-top:15px">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    
                    @include('errors.list')

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <h4>General</h4>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Gender</label>
                            <div class="col-md-6">
                                <input type="radio" name="gender" id="male" value="M" {{(old('gender')=='M')?'checked="checked"':''}}> <label for="male">Male</label>

                                <input type="radio" name="gender" id="female" value="F" {{(old('gender')=='F')?'checked="checked"':''}}> <label for="female">Female</label>
                            </div>
                        </div>

<?php /*
                        <div class="form-group">
                            <label class="col-md-4 control-label">Date of Birth</label>
                            <div class="col-md-6">
                                <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                            </div>
                        </div>
*/ ?>


                        <?php /*

                                    @foreach ($countries as $countryKey => $countryName)
                                        <option value="{{ $countryKey }}"  {{ ($countryKey=="AE")?"selected":"" }} >{{ $countryName }}</option>
                                    @endforeach

                                                            <div class="form-group">
                            <label class="col-md-4 control-label">Country</label>
                            <div class="col-md-6">
                                <select class="form-control" name="country">
                                    <option value="AE" selected="">United Arab Emirates</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">City</label>
                            <div class="col-md-6">
                                <select class="form-control" name="country">
                                    @foreach ($cities as $key => $val)
                                    <option value="{{$key}}">{{ $val }}</option>
                                    @endforeach
                                s</select>
                            </div>
                        </div>

                        */ ?>




                        <div class="form-group">
                            <label class="col-md-4 control-label">Mobile</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="mobile" placeholder="Ex. 0501234567" value="{{ old('mobile') }}">
                            </div>
                        </div>



                        <hr>

                        <h4>Choose login detail</h4>
                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">New Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" placeholder="Choose a your new password" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" placeholder="Retype your new password"  name="password_confirmation">
                            </div>
                        </div>

                        <hr>

<?php

                        // <h4>Choose username</h4>

                        // <div class="form-group">
                        //     <label class="col-md-4 control-label">Username</label>
                        //     <div class="col-md-6">
                        //         <input type="username" class="form-control" name="username" value="{{ old('username') }}" v-model="username">
                        //         <br>
                        //         <div style="font-style: italic">
                        //             Your profile will be www.minidel.com/@{{username}}
                        //         </div>
                            
                        //     </div>
                        // </div>

?>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')
<script type="text/javascript">

    var vm = new Vue({
    el: '#defaultMainContainer',
    data:{   
    },
  })

</script>
@stop