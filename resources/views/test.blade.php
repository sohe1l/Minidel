@extends('layouts.default')

@section('head')
<style type="text/css">
.media { border-bottom: 1px solid #B5B5B5; padding:10px 0;}
</style>
@endsection


@section('content')

<div class="row" >
  <div class="col-xs-8">
    <h1>Last Orders Today</h1>
    
    @foreach (\App\Order::orderBy('created_at','desc')->take(20)->get() as $order)

    <div class="media">
      <div class="media-left media-top">
        <a href="#">
          <img src="/img/logo/{{ $order->store->logo or 'placeholder.svg' }}" class="media-object hidden-xs">
          <img src="/img/cover/{{ $order->store->cover or 'placeholder.svg' }}" class="media-object visible-xs">
        </a>
      </div>
      <div class="media-body">
        <h4 class="media-heading">{{ $order->store->name }}</h4>

        Type: {{ $order->type }}
        Price: {{ $order->price + $order->fee}}
        {{ ($order->discount!=0)? round($order->price * $order->discount / 100 ,2) : '' }}
        {{ $order->created_at }}

        <table class="table">
            @foreach(json_decode($order->cart) as $item)
            <tr>
              <td>{{$item->title}}
                @if($item->options)
                <div>
                  @foreach($item->options as $option)
                  <span class="cart-options" v-repeat="item.options">
                    <b>{{$option->name}}:</b>
                    <span class="cart-options" v-repeat="selects">{{$option->name}} </span> 
                  </span>
                  @endforeach
                </div>
                @endif
              </td>
              <td>{{$item->quan}}</td>
              <td>{{$item->quan * $item->price}}</td>
            </tr>
            @endforeach
        </table>

      </div>
    </div>

    @endforeach



  </div>
</div>


@endsection




@section('footer')

@endsection