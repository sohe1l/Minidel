@extends('layouts.default')

@section('content')

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Reports</li>
</ol>

<div class="row">
  <div class="col-md-3">
  
  <div style="height:20px">&nbsp;</div>

    <ul class="nav nav-pills nav-stacked">
      <li role="presentation" class="active"><a href="#">Orders</a></li>
      <li role="presentation"><a href="/manage/{{$store->slug}}/billing">Billing</a></li>
    </ul>

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
    @foreach ($store->orders as $order)
        <tr>
            <td><a href="/manage/{{$store->slug}}/reports/order/{{ $order->id }}">{{ $order->id }}</a></td>
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
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="4">Total Discount</th>
            <th>{{ $totalDiscount }}</th>
            <th></th>
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

