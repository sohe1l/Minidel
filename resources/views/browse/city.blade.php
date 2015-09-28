@extends('layouts.default')

@section('content')
<div class="row">
  <div class="col-md-3">
    <h4>Areasw</h4>
    @foreach ( $city->areas as $area)
        <div><a href="/dubai/{{$area->slug}}">{{$area->name}}</a></div>
    @endforeach
  </div>
  <div class="col-md-9"> 
    <h1>{{ $city->name }}</h1>

    @foreach($city->stores as $store)
        <h3>{{ $store->name }}<br>
            <small>{{ $store->info }}</small>
        </h3>
    @endforeach



  </div>

</div>















@endsection