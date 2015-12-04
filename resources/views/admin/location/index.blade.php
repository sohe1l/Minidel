@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/superadmin/">Super Admin</a></li>
    <li class="active">Locations</li>
  </ol>
@endsection

@section('content')
    <h1>Locations</h1>
    <h2>Countries</h2>
    <div>Please select a country below:</div>
    <a href="/superadmin/locations/ae">United Arab Emirates</a>
@endsection