@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">Delivery</li>
  </ol>
@endsection


@section('content')
<div class="row">
  <div class="col-md-3">
    <h4>Filters</h4>
    
  </div>
  <div class="col-md-9"> 
    <h1>{{ $city->name }} - {{ $area->name }} - {{ $building->name }}</h1>

    @foreach($allStores as $store)
        <h3>{{ $store->name }}<br>
            <small>{{ $store->info }}</small>
            <small>{{ $store->pivot->min }} {{ $store->pivot->fee }} {{ $store->pivot->feebelowmin }} </small>
        </h3>
    @endforeach


  </div>

</div>


@endsection