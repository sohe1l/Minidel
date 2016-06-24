@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li class="active">Browse</li>
  </ol>
@endsection

@section('content')




<div class="row">
    <div class="col-xs-4" style="text-align: center">
    </div>
    <div class="col-xs-8">
        <br><br>
        <h2 style="margin-top: 15px !important;">Browse Chain: {{$chain->name}}</h2>
    </div>

<div class="row">

    <div class="col-xs-12 col-sm-7 col-md-8 col-lg-8">

    @foreach($chain->stores as $store)
        {{ $store->name }}
    @endforeach

    </div>
</div>





@endsection
@section('footer')

@endsection