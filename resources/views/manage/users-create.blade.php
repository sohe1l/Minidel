@extends('layouts.default')

@section('content')

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li><a href="/manage/{{$store->slug}}/users">Users</a></li>
  <li class="active">New User</li>
</ol>

<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'users'))
  </div>
  <div class="col-md-9">
        <h3>Add a New User<br>
            <small>Users can manage your store on your behalf.</small>
        </h3>

                    @include('errors.list')

                    {!! Form::open(array('url' => '/manage/'.$store->slug.'/users', 'class'=>'form-horizontal' ) ) !!}

                        <div class="form-group">
                            <label class="col-md-4 control-label">Role</label>
                            <div class="col-md-6">
                                {!! Form::select('role_id', $roles_list, null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Email</label>
                            <div class="col-md-6">
                                {!! Form::text('email', null, $attributes=array('class'=>'form-control')); !!}
                            </div>
                        </div>

            
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="float:right">
                                    Create
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}


      <br><br><br>

      <table class="table">
        <tr>
          <th>Role</th>
          <th>Take Order</th>
          <th>Manage Setting</th>
          <th>Add/Remove Users</th>
        </tr>
        <tr>
          <td>Store Owner</td>
          <td><span class="glyphicon glyphicon-ok"></span></td>
          <td><span class="glyphicon glyphicon-ok"></span></td>
          <td><span class="glyphicon glyphicon-ok"></span></td>
        </tr>
        <tr>
          <td>Store Manager</td>
          <td><span class="glyphicon glyphicon-ok"></span></td>
          <td><span class="glyphicon glyphicon-ok"></span></td>
          <td></td>
        </tr>
        <tr>
          <td>Store Staff</td>
          <td><span class="glyphicon glyphicon-ok"></span></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      <small style="font-weight: bold; text-align: right">* Including other owners</small>
    </div>
</div>




@endsection

@section('footer')
@endsection