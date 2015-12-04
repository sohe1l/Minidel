@extends('layouts.default')

@section('breadcrumb')
    <ol class="breadcrumb">
      <li><a href="/">Home</a></li>
      <li><a href="/manage/">Manage</a></li>
      <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
      <li><a href="/manage/{{$store->slug}}/reports">Reports</a></li>
      <li><a href="/manage/{{$store->slug}}/reports/billing">Billing</a></li>
      <li class="active">{{ $year .'-'. $month }}</li>
    </ol>
@endsection


@section('content')



<div class="row">
  <div class="col-md-3">
    @include('manage.reports.nav', array('active'=>'billing'))
  </div>
  <div class="col-md-9">


    <h2>Reports: Billing<br>
        <small>Here you can view your store billing details.</small>
    </h2>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Reference</th>
            <th>Toral Debit</th>
            <th>Total Credit</th>
            <th>Order</th>
        </tr>
    <?php $totalDebit = 0; $totalCredit = 0; ?>
    @foreach ($transactions as $tran)
        <tr>
            <td>{{ $tran->id }}</td>
            <td>{{ $tran->reference }}</td>
            <td>{{ ($tran->type=='debit')?$tran->amount:'' }}</td>
            <td>{{ ($tran->type=='credit')?$tran->amount:'' }}</td>
            <td>{{ $tran->created_at }}</td>
        </tr>
    <?php
        if($tran->type == 'debit') $totalDebit += $tran->amount;
        if($tran->type == 'credit') $totalCredit += $tran->amount;
    ?>
    @endforeach
        <tr>
            <th colspan="2">Total</th>
            <th>{{ $totalDebit }}</th>
            <th>{{ $totalCredit }}</th>
            <th></th>
        </tr>
        <tr>
            <th colspan="2"></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="2"></th>
            <th></th>
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