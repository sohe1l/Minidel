@extends('layouts.default')

@section('content')


<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'users'))
  </div>
  <div class="col-md-9">

    <h2>Store Users<br>
        <small>Users can manage your store on your behalf with the given permissions.</small>
    </h2>

    <div class="row">
      <div class="col-lg-6">

      </div><!-- /.col-lg-6 -->

      <div class="col-lg-6" style="text-align:right">
        <a href="/manage/{{$store->slug}}/users/create" class="btn btn-default">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          New User
        </a>
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->

    <br>

    <div class="panel panel-default">
      <div class="panel-heading">Users</div>
      <table class="table">
          <tr>
            <th>Name</b></th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Role</th>
            <th style="width:50px"> </th>
          </tr>
               
        @foreach ($store->users as $user)
          <tr>
            <td><b>{{ $user->name }}</b></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->mobile }}</td>
            <td>{{ $roles_list[$user->pivot->role_id] }}</td>
            <td>
              <a href="javascript:deleteUser({{ $user->id }})" title="Delete">
                <span class="glyphicon glyphicon-remove"></span>
              </a>
            </td>
          </tr>
        @endforeach
        </table>
    </div>


  </div>

</div>



@endsection

@section('footer')
@endsection