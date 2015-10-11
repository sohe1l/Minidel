@extends('layouts.default')

@section('content')

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Users</li>
</ol>

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
          Add New User
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


<br><br><bR>
      <table class="table">
        <tr>
          <th>Role</th>
          <th>Take Order</th>
          <th>Manage Setting</th>
          <th>Add/Remove Users*</th>
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

<div id="insert"></div>


@endsection


@section('footer')
  <script type="text/javascript">
      function deleteUser(id){
        var r = confirm("You are about to delete a user role. Are you sure?");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/users/'+id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }
  </script>

@endsection
