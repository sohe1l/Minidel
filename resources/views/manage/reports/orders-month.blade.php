@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li><a href="/manage/{{$store->slug}}/reports">Reports</a></li>
  <li><a href="/manage/{{$store->slug}}/reports/orders">Orders</a></li>
  <li class="active">{{$year}}-{{$month}}</li>
</ol>
@endsection

@section('content')



<div class="row">
  <div class="col-md-3">
    @include('manage.reports.nav', array('active'=>'orders'))
  </div>
  <div class="col-md-9">


    <h2>Reports: Orders<br>
        <small>Here you can view your store previous orders and details.</small>
    </h2>

    <table class="table">
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Type</th>
            <th>Status</th>
            <th>Total Price</th>
            <th>Discount</th>
            <th>Order Date</th>
        </tr>
    <?php $total = 0; $totalDiscount = 0; ?>
    @foreach ($orders as $order)
        <tr>
            <td><a href="/manage/{{$store->slug}}/reports/orders/order/{{ $order->id }}">{{ $order->id }}</a></td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->type }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->price + $order->fee}}</td> 
            <td>{{ ($order->discount!=0)? round($order->price * $order->discount / 100 ,2) : 0 }}</td> 
            <td>{{ $order->created_at }}</td>
        </tr>
    <?php 
        $total += $order->price + $order->fee;
        if($order->discount != 0 )
          $totalDiscount += round($order->price * $order->discount / 100 ,2); 
    ?>
    @endforeach
        <tr>
            <th colspan="4">Total</th>
            <th>{{ $total }}</th>
            <th>{{ $totalDiscount }}</th>
            <th></th>
        </tr>
        <tr>
            <th colspan="4">Total After Discount</th>
            <th>{{ $total - $totalDiscount }}</th>
            <th></th>
            <th></th>
        </tr>
    </table>

  </div>

</div>




<div id="insert">
</div>



@endsection

@section('footer')


@endsection

