@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Payments</li>
</ol>
@endsection

@section('head')
<style type="text/css">
  input[type=checkbox]:checked + label {
    color: #fda514;
  } 
</style>
@endsection

@section('content')




<div class="row">
  <div class="col-md-3">@include('manage.nav', array('active'=>'payments'))</div>
  <div class="col-md-9">
      
    <h2>Payment Methods<br>
        <small>Below you can select the payments method that you wish to offer for your store.</small>
    </h2>

    @include('errors.list')

    {!! Form::open() !!}
  
    @foreach (\App\paymentType::orderBy('name', 'asc')->get() as $payment)
        <div class="form-group">
          {!! Form::checkbox('payments[]', $payment->id, in_array($payment->id, $paymentslist) ? true : false, ['id'=>'payment'.$payment->id] ); !!}
          <label for="payment{{$payment->id}}">{{ $payment->name }}</label>
        </div>
    @endforeach

    <div>Please note that the Credit Card payment is still not available. However feel free to select it as it will be activated for your store once its available. Kindly note the bank charge rate for this service is (2.5%) from the total order amount. You can view the fee for each order the billing section.</div>
    <div style="text-align: right"><button type="submit" class="btn btn-default">Save payments methods</button></div>

    {!! Form::close() !!}















  </div>

</div>


<div id="insert"></div>



@endsection

@section('footer')


@endsection
