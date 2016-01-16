@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li class="active">Contact</li>
</ol>

@endsection

@section('content')

    
    

    <div class="panel panel-default">
        <div class="panel-body">

@if(!isset($done))

            <h3>Contact Us</h3>

            <b>Please send us a message through the following form.<br>
            We will get back to you maximum within 24 hours.</b>

            <br><br>

            {!! Form::open(array('class'=>'form-horizontal', 'url' => '/pages/contact' )) !!}
            
            <div class="form-group">
                <label class="col-md-4 control-label">Name</label>
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        {!! Form::text('name' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Mobile</label>
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                        {!! Form::text('mobile' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Store Email</label>
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        {!! Form::text('email' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Message</label>
                <div class="col-md-8">
                    {!! Form::textarea('info' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    {!! Form::submit('Send Message!', $attributes=array('class'=>'btn btn-primary')); !!}
                </div>
            </div>
            {!! Form::close() !!}

@else
    <div style="text-align: center">
        <span style="background-color: #3c763d; font-size:1.5em; border-radius: 2em; margin-top: -10px;" class="badge"><span class="glyphicon glyphicon-ok"></span></span> <span style="font-size:2em; color:#3c763d">Thanks!</span>
    </div>
    
    <div style="color:#3c763d; font-size: 1.5em; text-align: center; margin-top:40px;">
        We have recieved your message and will get back to you within the next 24 hours.
    </div>

@endif

        </div>
    </div>



@stop
