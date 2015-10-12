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
      <li role="presentation"><a href="/manage/{{$store->slug}}/reports">Orders</a></li>
      <li role="presentation" class="active"><a href="#">Billing</a></li>
    </ul>

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
            <th>Order Date</th>
        </tr>
    <?php $totalDebit = 0; $totalCredit = 0; ?>
    @foreach ($store->transactions as $tran)
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

