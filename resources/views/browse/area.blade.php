@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li><a href="/browse/">Browse</a></li>
    <li><a href="/browse/{{$city->slug}}">{{$city->name}}</a></li>
    <li class="active">{{$area->name}}</li>
  </ol>
@endsection



@section('content')
<div class="row">
  <div class="col-md-3">
    <h4>Filters</h4>
    
  </div>
  <div class="col-md-9"> 
    <h1>{{ $city->name }} - {{ $area->name }}</h1>


    @foreach($area->stores as $store)
        <h3><a href="/{{$store->slug}}">{{ $store->name }}</a><br>
            <small>{{ $store->info }}</small>
        </h3>
    @endforeach


  </div>

</div>


@endsection