@extends('layouts.default')


@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li><a href="/browse/">Browse</a></li>
    <li class="active">Promotions</li>
  </ol>
@endsection



@section('content')

<div class="row">
  <div class="col-md-3">
  </div>
  <div class="col-md-9"> 





        <div class="row" style="padding: 20px 10px;">
            <div class="col-xs-12">
                <div style="font-weight: bold; color:#f05f40; font-size:2em; font-family:lane;">Current Promotions</div>
                @foreach( \App\Promo::Active()->orderBy('start_date')->with('store')->get() as $promo )

<div class="listing storeListing clearfix">
    <div class="col-sm-2">
      <a href="/{{$promo->store->slug}}/order">
        <img src="/img/logo/{{ $promo->store->logo || 'placeholder.svg'}}" class="img-responsive hidden-xs">
        <img src="/img/cover/{{ $promo->store->cover || 'placeholder.svg'}}" class="img-responsive visible-xs">
      </a>
    </div>
    <div class="col-sm-10">
        <div class="title"><a href="/{{$promo->store->slug}}/order">{{ $promo->store->name }}</a></div>
        <div><i>{{$promo->value}}% off on all items.</i></div>
        <div>{{$promo->text}}</div>
        <div>Promotion ends on {{$promo->end_date}}</div>
    </div>
</div>



                @endforeach
            </div>
        </div>


  </div>

</div>


@endsection
@section('footer')

@stop