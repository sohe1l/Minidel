@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/superadmin/">Super Admin</a></li>
    <li class="active">Order Times</li>
  </ol>
@endsection

@section('content')




<table class="table whiteBG">
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Order</th>
        <th>Store</th>
        <th>Status</th>
        <th>Timestamp</th>

    </tr>
  @foreach($times as $time)
   <tr class="{{ ($time->status!='delivered')?'success':'' }} {{ ($time->status!='pending')?'danger':'' }}">
    <td>{{$time->id }}</td>
    <td>{{$time->user_id }}</td>
    <td>{{$time->order_id }}</td>
    <td>{{$time->store_id }}</td>
    <td>{{$time->status }}</td>
    <td>{{$time->timestamp }}</td>
   </tr>
@endforeach
</table>

<div style="text-align: center">
    {!! $times->render() !!}
</div>



@endsection


@section('footer')

@endsection

