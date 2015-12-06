@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li><a href="/manage/{{$store->slug}}/reports">Reports</a></li>
  <li><a href="/manage/{{$store->slug}}/reports/orders">Orders</a></li>
  <li class="active">Order {{ $order->id }}</li>
</ol>
@endsection

@section('content')


<div class="row">
  <div class="col-md-3">
    @include('manage.reports.nav', array('active'=>'orders'))
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
            <span class="label label-primary">{{ $order->paymentType->name }}</span>
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
              <td>{{  $order->fee!=0?$order->fee.' fee':''}}</td>
              <td></td>
              <td><b>{{ $order->price + $order->fee }}</b></td>
            </tr>


            @if($order->discount != 0)
            <tr>
              <td></td>
              <td>{{ $order->discount }} % discount</td>
              <td><b>{{ round($order->price * $order->discount / 100 ,2) }}</b></td>
            </tr>
            <tr>
              <td></td>
              <td><b>Payable</b></td>
              <td><b>{{ $order->price + $order->fee - round($order->price * $order->discount / 100 ,2)    }}</b></td>
            </tr>
            @endif








          </table>

          @if($order->user_address)
          <blockquote v-show="orders[selectedIndex].user_address">
            @{{ orders[selectedIndex].user_address.name }}
            @{{ orders[selectedIndex].user_address.phone }} -
            @{{ orders[selectedIndex].user_address.area.name }}
            @{{ orders[selectedIndex].user_address.building.name }} -
            @{{ orders[selectedIndex].user_address.unit }}
            @{{ orders[selectedIndex].user_address.info }}
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

