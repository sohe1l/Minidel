@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li><a href="/dashboard/">Dashboard</a></li>
    <li class="active">General</li>
  </ol>
@endsection

@section('content')


<div class="row">
  <div class="col-md-3 hidden-xs">
  @include('dashboard.nav', array('active'=>'general'))
  </div>
  <div class="col-md-9 panel panel-default">
    <div class="panel-body">
    <h2>General<br>
        <small>Manage your profile information addresses</small>
    </h2>

  @include('errors.list')

    {!! Form::model($user, ['files' => true, 'class'=>'form-horizontal', 'url'=>'/dashboard/dp' ]) !!}

      <h4>Profile Picture</h4>

        <div class="form-group">
            <label class="col-md-4 control-label">Current Picture</label>
            <div class="col-md-6">
                <img src="/img/user/{{ $user->dp or 'placeholder.svg' }}" class="img-thumbnail">
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-4 control-label">Update Picture</label>
            <div class="col-md-4">
                {!! Form::file('imagefile', $attributes=array('class'=>'')); !!}
            </div>
            <div class="col-md-2">
                {!! Form::submit('Update Picture!', $attributes=array('class'=>'btn btn-sm btn-primary')); !!}
            </div>
        </div>
    {!! Form::close() !!}

    <hr>

    {!! Form::model($user, ['class'=>'form-horizontal', 'url'=>'/dashboard/general' ]) !!}

        <h4>General</h4>

        <div class="form-group">
            <label class="col-md-4 control-label">Name</label>
            <div class="col-md-6">
              {!! Form::text('name', null, ['class' => 'form-control']) !!}                              
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Gender</label>
            <div class="col-md-6">
              {!! Form::radio('gender', 'M', null, ['id'=>'male']) !!}
              {!! Form::label('male', 'Male') !!}

              {!! Form::radio('gender', 'F', null, ['id'=>'female']) !!}
              {!! Form::label('female', 'Female') !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Date of Birth</label>
            <div class="col-md-6">
              {!! Form::date('dob', null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Mobile</label>
            <div class="col-md-6">
              {!! Form::text('mobile', null, array('class' => 'form-control', 'placegolder'=>'Ex. 0501234567')) !!}
            </div>
        </div>


        <hr>

        <h4>Username</h4>
        <div class="form-group">
            <label class="col-md-4 control-label">Username</label>
            <div class="col-md-6">
              {!! Form::text('username', null, ['class' => 'form-control', 'v-model'=>'username']) !!}  
              <br>
              <div style="font-style: italic">
                  Your profile will be www.minidel.com/username
              </div>
            </div>
        </div>




        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Update Information
                </button>
            </div>
        </div>
    {!! Form::close() !!}
    </div>
    </div>
</div>




<div id="insert">
</div>



@endsection

@section('footer')
  <script type="text/javascript">
      function deleteAddress(id){
        var r = confirm("Are you sure you want to delete the address ?");
        if (r == true) {
          $('#insert').html('<form action="/dashboard/address/'+id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }
  </script>
@endsection



