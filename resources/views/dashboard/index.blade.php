@extends('layouts.default')



@section('head')

<style type="text/css">

.listing{
    padding:1em;
    border-bottom: 1px solid #e7e7e7;
}
.listing img{
}
.listing .title{
    font-size:120%;
    font-weight: bold;
}

.quickOrder{
    padding:1em;
    border-bottom: 1px solid #e7e7e7;
}





@media(max-width:767px){

}
@media(min-width:768px){
}
@media(min-width:992px){

}
@media(min-width:1200px){
}






</style>



@endsection



@section('content')

<div class="row">
  <div class="col-md-3">
    <h3>Quick Order</h3>
        <div class="quickOrder">
            2 Lattee FROM Mensch Cafe DELIVER Home <br>
            <button type="button" class="btn btn-primary btn-xs">Order</button>
        </div>
        
        <div class="quickOrder">
            2 Lattee FROM Mensch Cafe DELIVER Home <br>
            <button type="button" class="btn btn-primary btn-xs">Order</button>
        </div>

        <div class="quickOrder">
            2 Lattee FROM Mensch Cafe DELIVER Home <br>
            <button type="button" class="btn btn-primary btn-xs">Order</button>
        </div>
  </div>
  <div class="col-md-9">
    <h2>Stores that serve you</h2>


    @if(!$user->hasAddresses())
        <div class="alert alert-warning" role="alert">You need to add your address to be able to make an order.</div>
        <a href="/dashboard/address/create" class="btn btn-default">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          New Address
        </a>
    @endif

    @foreach($merged as $store)
        <div class="listing clearfix">
            <div class="col-sm-2">
                <img src="/img/logo/{{ $store->logo or 'placeholder.svg' }}" class="img-responsive hidden-xs">
                <img src="/img/cover/{{ $store->cover or 'placeholder.svg' }}" class="img-responsive visible-xs">
            </div>
            <div class="col-sm-10">
                <div class="title"><a href="/store/{{$store->city->slug}}/{{$store->area->slug}}/{{$store->slug}}">{{ $store->name }}</a></div>
                <div>
                    <span class="label label-info">{{ $store->isOpenNow('Normal Openning') == 'true'?'Open Now':''  }}</span>
                    <span class="label label-danger">{{ $store->isOpenNow('Normal Openning') == 'false'?'Closed Now':''  }}</span>
                    <span class="label label-success">{{ $store->isOpenNow('Building Delivery') == 'true'?'Deliveres Now':''  }}</span>
                </div>
                <div>{{ $store->info }}</div>


            </div>



        </div>
    @endforeach










  </div>
</div>



@stop