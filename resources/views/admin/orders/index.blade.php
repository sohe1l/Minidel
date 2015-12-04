@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/superadmin/">Super Admin</a></li>
    <li class="active">Orders</li>
  </ol>
@endsection

@section('content')

@include('errors.list')



<table class="table whiteBG">
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Store</th>
        <th>Price</th>
        <th>Created At</th>
    </tr>
  @foreach($orders as $order)
   <tr class="{{ ($order->status!='delivered')?'danger':'' }}">
    <td>{{$order->id}}</td>
    <td>{{$order->user_id}}</td>
    <td>{{$order->store_id}}</td>
    <td>{{$order->price}}</td>
    <td>{{$order->created_at}}</td>
    

   </tr>
@endforeach
</table>

<div style="text-align: center">
    {!! $orders->render() !!}
</div>


<div id="insert">
</div>

@endsection


@section('footer')
@endsection

