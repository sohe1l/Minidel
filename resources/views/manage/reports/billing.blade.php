@extends('layouts.default')

@section('breadcrumb')
    <ol class="breadcrumb">
      <li><a href="/">Home</a></li>
      <li><a href="/manage/">Manage</a></li>
      <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
      <li><a href="/manage/{{$store->slug}}/reports">Reports</a></li>
      <li class="active">Billing</li>
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




    <h3>Monthly Breakdown</h3>
    <table class="table">
        <tr>
            <th>Year-Month</th>
            <th>Count Debit</th>
            <th>Total Debit</th>
            <th>Count Credit</th>
            <th>Total Credit</th>
        </tr>
    @foreach ($monthlyBreakdown as $order)
        <tr>
            <td><a href="/manage/{{$store->slug}}/reports/billing/{{ $order->yearMonth }}">{{ $order->yearMonth }}</a></td>
            <td>{{ $order->countDebit }}</td>
            <td>{{ $order->debit / 100 }}</td>
            <td>{{ $order->countCredit }}</td>
            <td>{{ $order->credit / 100 }}</td> 
        </tr>

    @endforeach
    </table>




    @if(in_array(\Auth::user()->id, \Config::get('vars.superAdmins')))
      <div class="panel panel-warning">
        <div class="panel-heading">Manager Panel: Add Transaction</div>
        <div class="panel-body">

        @include('errors.list')


        {!! Form::open(array('url' => '/manage/'.$store->slug.'/reports/billing/transaction/create', 'class'=>'form-horizontal' ) ) !!}

          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Store</label>
            <div class="col-sm-10">
              <input type="input" class="form-control" value="{{ $store->name }}" disabled="disabled">
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Amount</label>
            <div class="col-sm-10">
              <input type="input" class="form-control" id="amount" name="amount" placeholder="Amount">
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Type</label>
            <div class="col-sm-10">
              <select class="form-control" id="type" name="type">
                <option value="debit">Debit (we are paying store)</option>
                <option value="credit">Credit (store is paying us money)</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Reference</label>
            <div class="col-sm-10">
              <input type="input" class="form-control" id="reference" name="reference" placeholder="Reference for payment">
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default">Submit Transaction</button>
            </div>
          </div>

        {!! Form::close() !!}
        </div>
      </div>
    @endif








  </div>

</div>




<div id="insert">
</div>



@endsection

@section('footer')


@endsection

