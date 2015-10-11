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
      <li role="presentation" class="active"><a href="#">Reports</a></li>
    </ul>

  </div>
  <div class="col-md-9">


    <h2>Reports<br>
        <small>Here you can view your store previous orders and details.</small>
    </h2>

    <table class="table">
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Type</th>
            <th>Status</th>
            <th>Total Price</th>
            <th>Date</th>
        </tr>
    <?php $total = 0; ?>
    @foreach ($store->orders as $order)
        <tr>
            <td><a href="/manage/{{$store->slug}}/reports/order/{{ $order->id }}">{{ $order->id }}</a></td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->type }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->price + $order->fee}}</td> <?php $total += $order->price + $order->fee; ?>
            <td>{{ $order->created_at }}</td>
        </tr>

    @endforeach
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>{{ $total }}</th>
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

