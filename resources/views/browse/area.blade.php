@extends('layouts.default')

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/dashboard/">Dashboard</a></li>
  <li class="active">Order</li>
</ol>


@section('content')
<div class="row">
  <div class="col-md-3">
    <h4>Filters</h4>
    
  </div>
  <div class="col-md-9"> 
    <h1>{{ $city->name }} - {{ $area->name }}</h1>


    @foreach($area->stores as $store)
        <h3>{{ $store->name }}<br>
            <small>{{ $store->info }}</small>
        </h3>
    @endforeach


  </div>

</div>


@endsection