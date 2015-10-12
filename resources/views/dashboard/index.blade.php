@extends('layouts.default')



@section('head')

<meta name="csrf-token" content="{{ csrf_token() }}" />


<style type="text/css">
.quickOrder{
    padding:1em;
    border-bottom: 1px solid #e7e7e7;
}
.quickOrder:first-child{
    padding-top:0em;
}
</style>



@endsection



@section('content')

<ol class="breadcrumb hidden-xs">
  <li><a href="/">Home</a></li>
  <li class="active">Dashboard</li>
</ol>

@if ($user->addresses->count() == 0)
  <div class="jumbotron visible-xs" style="text-align:center">
    <h4><a href="/dashboard/address/create/">Add an Address to be able to make orders</a></h4>
  </div>
@endif

<?php /* 
<div class="visible-xs" style="margin-bottom:20px;">
  <form method="GET" action="/search/">
    <div id="custom-search-input">
        <div class="input-group col-md-12">
            <input type="text" class="search-query form-control" name="q" placeholder="Search" />
            <span class="input-group-btn">
                <button class="btn btn-danger" type="button">
                    <span class=" glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
    </div>
  </form>
</div>
*/ ?>

<div class="row">
  <div class="col-sm-4">


    <h4 class="hidden-xs">Make New Order</h4>

    <div class="btn-group" role="group" style="width: 100%;">

        <a href="/dashboard/order/?type=mini" class="btn btn-primary btn-lg" style="width:50%">
          <span class="glyphicon glyphicon-home" aria-hidden="true"></span> Mini
        </a>
        <?php /*
        <a href="/dashboard/order/?type=delivery" class="btn btn-default btn-block btn-lg">
          <span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Delivery
        </a>
        */ ?>
        <a href="/dashboard/order/?type=pickup" class="btn btn-default btn-lg" style="width:50%">
          <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Pickup
        </a>
    </div>


    <br><br>


    <div class="hidden-xs">
      <h4>Recent Resturants</h4>

      <form method="GET" action="/search/">
      <div id="custom-search-input">
          <div class="input-group col-md-12">
              <input type="text" class="search-query form-control" name="q" placeholder="Search" />
              <span class="input-group-btn">
                  <button class="btn btn-danger" type="button">
                      <span class=" glyphicon glyphicon-search"></span>
                  </button>
              </span>
          </div>
      </div>
    </form>

      @foreach($recent as $order)
        

        <div class="media">
          <div class="media-left">
            <a href="/store/{{ $order->store->city->slug }}/{{ $order->store->area->slug }}/{{ $order->store->slug }}/order/">
              <img class="media-object" src="/img/logo/{{ $order->store->logo or 'placeholder.svg' }}" style="width:50px;">
            </a>
          </div>
          <div class="media-body">
            <h4 class="media-heading">
              <a href="/store/{{ $order->store->city->slug }}/{{ $order->store->area->slug }}/{{ $order->store->slug }}/order/">
                {{ $order->store->name }}
              </a>
            </h4>
            {{ $order->store->info }}
          </div>
        </div>

      @endforeach
    </div>














  </div>
  <div class="col-sm-5">











<h4>Quick Order</h4>

<div v-show="orderComplete" class="alert alert-success" role="alert" style="margin-top:10px;">
  Order recieved successfully.
</div>
<div v-show="!orderComplete">    
    @forelse($user->orders()->where('status','delivered')->orderBy('id','desc')->take(3)->get() as $order)
        <div class="quickOrder">
          [ {{ $order->type }} ]
          @foreach(json_decode($order->cart) as $item)
            {{ $item->quan }} <small><b>X</b></small> {{ $item->title }} 
            
            @if(property_exists($item,'options') && is_array($item->options) && count($item->options)>0)
            [
                @foreach($item->options as $option)
                    {{ $option->name }}: 
                    <span style="font-style: italic">
                        @foreach($option->selects  as $subOption) {{ $subOption->name }} @endforeach
                    </span>
                @endforeach
            ]
            @endif
          @endforeach

        <div>from <b>{{ $order->store->name }}</b></div>
        <div>{{ $order->instructions }}</div>
        <button type="button" class="btn btn-primary btn-xs" v-on="click: placeRegular({{ $order->id }})">Order</button>
      </div>
    @empty
      <div style="font-style: italic">You did not make any orders yet.</div>
    @endforelse

</div>






  </div>
  <div class="col-sm-3 hidden-xs">

    <h4>Your Addresses <a style="float:right" href="/dashboard/address/"><span class="glyphicon glyphicon-edit"></span></a></h4>


    @forelse($user->addresses as $address)
        <div><b>{{ $address->name }}</b></div>
        <div>{{ $address->city->name }}, {{ $address->area->name }}, {{ $address->building->name }}</div>
        <div>{{ $address->unit }}, {{ $address->info }}</div>
        <div>&nbsp;</div>
    @empty
        No address. <a href="/dashboard/address/create">Add Address</a>
    @endforelse


  </div>
</div>



@stop

@section('footer')
<script type="text/javascript">
  
  var vm = new Vue({
    el: '#defaultMainContainer',
    data:{
      orderComplete : false,
    },
    methods:{
      placeRegular: function(id){
        var that = this;
        $.ajax({
          type: "POST",
          url: "/dashboard/regular",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: {
            order: id,
            timeout: 15000,
            dataType: 'json'
          }
        })
        .done(function(data) {
          if(data["error"]==1){
            alert(data["message"]);
          }else{
            that.orderComplete = true;
            setTimeout(function(){location.href="/dashboard/orders/"} , 100); 
          }
        })
        .fail( function(xhr, status, error) {
          alert("Some Error Occured!");
        });
      } // place order
    }
  });

</script>
@stop