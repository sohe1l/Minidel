@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Reports</li>
</ol>
@endsection

@section('content')



<div class="row">
  <div class="col-md-3">
    @include('manage.reports.nav', array('active'=>'orders'))
  </div>
  <div class="col-md-9">


    <h2>Reports<br>
        <small>Here you can view your store orders and billing reports.</small>
    </h2>

    <h3>Number of orders per day</h3>
    <canvas style="width: 100%" height:"300" id="dailyOrdersChart"></canvas>

    <br><br><br><br>

    <h3>Total sales per day</h3>
    <canvas style="width: 100%" height:"300" id="dailyPriceChart"></canvas>


  </div>

</div>




<div id="insert">
</div>



@endsection

@section('footer')
<script type="text/javascript" src="/js/Chart.min.js"></script>
<script type="text/javascript">
    var ctx = $("#dailyOrdersChart").get(0).getContext("2d");

    var data = {
        labels: [ '{!! implode("','", array_keys($orders)) !!}' ],
        datasets: [
            {
                data: [ {!! implode(",", $orders)  !!} ]
            }
        ]
    };

    var ordersChart = new Chart(ctx).Bar(data);



    var ctxPrice = $("#dailyPriceChart").get(0).getContext("2d");

    var dataPrice = {
        labels: [ '{!! implode("','", array_keys($price)) !!}' ],
        datasets: [
            {
                data: [ {!! implode(",", $price)  !!} ]
            }
        ]
    };

    var priceChart = new Chart(ctxPrice).Bar(dataPrice);


</script>

@endsection

