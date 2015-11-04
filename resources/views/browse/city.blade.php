@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li><a href="/browse/">Browse</a></li>
    <li class="active">{{$city->name}}</li>
  </ol>
@endsection


@section('content')
<div class="row">
  <div class="col-md-3">
    <h4>City</h4>
    @foreach ( $city->areas as $area)
        <div><a href="/browse/dubai/{{$area->slug}}">{{$area->name}}</a></div>
    @endforeach
  </div>
  <div class="col-md-9"> 
    <h1>{{ $city->name }}</h1>

    @foreach($city->stores as $store)
        <h3><a href="/{{$store->slug}}">{{ $store->name }}</a><br>
            <small>{{ $store->info }}</small>
        </h3>
    @endforeach



  </div>

</div>















@endsection