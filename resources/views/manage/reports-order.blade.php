@extends('layouts.default')

@section('content')

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li><a href="/manage/{{$store->slug}}/reports">Reports</a></li>
  <li class="active">View Order</li>
</ol>

<div class="row">
  <div class="col-md-3">
  
  <div style="height:20px">&nbsp;</div>

    <ul class="nav nav-pills nav-stacked">
      <li role="presentation" class="active"><a href="#">Reports</a></li>
    </ul>

  </div>
  <div class="col-md-9">


    <h2>View Order<br>
        <small>Here you can view details of each order.</small>
    </h2>


          <h2> 
            {{ $order->user->name }}  
            <small>{{ $order->user->mobile }}</small>
          </h2>

          <h3>
            <span class="label label-primary">{{ $order->type }}</span>
            <span class="label label-warning">{{ $order->schedule }}</span>
          </h3>

          <br>

          <table class="table">
            <tr>
              <th>Item</th>
              <th>Quantity</th>
              <th>Total Price</th>
            </tr>

            @foreach(json_decode($order->cart) as $item)
            <tr>
              <td>{{$item->title}}
                @if($item->options)
                <div>
                  @foreach($item.options as $option)
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

            <tr>
              <td></td>
              <td></td>
              <td><b>{{ $order->price }}</b></td>

            </tr>

          </table>

          @if($order->user_address)
          <blockquote v-show="orders[selectedIndex].user_address">
            @{{ orders[selectedIndex].user_address.name }}
            @{{ orders[selectedIndex].user_address.phone }}
            @{{ orders[selectedIndex].user_address.area.name }}
            @{{ orders[selectedIndex].user_address.building.name }}
          </blockquote>     
          @endif           

          @if($order->instructions)
          <blockquote style="border-left:5px solid #F34F4F">
            {{ $order->instructions }}
          </blockquote>
          @endif

          @if($order->reason)
          <blockquote style="border-left:5px solid #F3884F">
                <b>Explanation:</b> {{ $order->reason }}
          </blockquote>
          @endif


          <div>
            <span style="text-transform: capitalize; font-weight: bold">
              Order Status: {{ $order->status }}
            </span>
          </div>
  </div>

</div>




<div id="insert">
</div>



@endsection

@section('footer')


@endsection

