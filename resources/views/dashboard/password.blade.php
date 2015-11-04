@extends('layouts.default')


@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li><a href="/dashboard/">Dashboard</a></li>
    <li class="active">Address</li>
  </ol>
@endsection


@section('content')

<div class="row">
  <div class="col-md-3 hidden-xs">
  @include('dashboard.nav', array('active'=>'password'))
  </div>
  <div class="col-md-9">
  
    <h2>Password<br>
        <small>Use the form below to change your password.</small>
    </h2>

  @include('errors.list')

    <div class="panel panel-default">
      <div class="panel-heading">Change your password</div>
      <div class="panel-body">
          
          {!! Form::model($user, ['class'=>'form-horizontal', 'url'=>'/dashboard/password' ]) !!}

              <div class="form-group">
                  <label class="col-md-4 control-label">Current Password</label>
                  <div class="col-md-6">
                      <input type="password" class="form-control" name="password" placeholder="Enter your current password" value="{{ old('password') }}">
                  </div>
              </div>

              <hr>

              <div class="form-group">
                  <label class="col-md-4 control-label">New Password</label>
                  <div class="col-md-6">
                      <input type="password" class="form-control" placeholder="Choose a your new password" name="newpassword">
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-md-4 control-label">Confirm Password</label>
                  <div class="col-md-6">
                      <input type="password" class="form-control" placeholder="Retype your new password"  name="newpassword_confirmation">
                  </div>
              </div>

              <div class="form-group">
                  <div class="col-md-6 col-md-offset-4">
                      <button type="submit" class="btn btn-primary">
                          Update
                      </button>
                  </div>
              </div>
          {!! Form::close() !!}
        </div>
      </div>



  </div>

</div>


<div id="insert">
</div>



@endsection

@section('footer')
@endsection



