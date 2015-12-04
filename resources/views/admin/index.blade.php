@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li class="active">Super Admin</li>
  </ol>
@endsection

@section('content')
    <a href="/superadmin/orders/pending/">Pending Orders</a><br><br>
    <a href="/superadmin/stores/">Browse Stores</a><br><br>

    <a href="/superadmin/stats/">Stats: Orders</a><br><br>
    <a href="/superadmin/stats/times">Stats: Times</a><br><br>

    <a href="/superadmin/locations/">Locations</a><br><br>





@stop