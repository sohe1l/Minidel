@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
@endsection

@section('head')

<style>
@media only screen and (max-width: 800px) {
    
    /* Force table to not be like tables anymore */
  #no-more-tables table, 
  #no-more-tables thead, 
  #no-more-tables tbody, 
  #no-more-tables th, 
  #no-more-tables td, 
  #no-more-tables tr { 
    display: block; 
  }
 
  /* Hide table headers (but not display: none;, for accessibility) */
  #no-more-tables thead tr { 
    position: absolute;
    top: -9999px;
    left: -9999px;
  }
 
  #no-more-tables tr { border: 1px solid #ccc; }
 
  #no-more-tables td { 
    /* Behave  like a "row" */
    border: none;
    border-bottom: 1px solid #eee; 
    position: relative;
    padding-left: 50%; 
    white-space: normal;
    text-align:left;
  }
 
  #no-more-tables td:before { 
    /* Now like a table header */
    position: absolute;
    /* Top/left values mimic padding */
    top: 6px;
    left: 6px;
    width: 45%; 
    padding-right: 10px; 
    white-space: nowrap;
    text-align:left;
    font-weight: bold;
  }
 
  /*
  Label the data
  */
  #no-more-tables td:before { content: attr(data-title); }
}
</style>

@endsection

@section('content')


    <h2>Running Orders<br>
        <small>Running orders are scheduled orders that runs every week.</small>
    </h2>

    <div style="text-align:right">
        <a href="/dashboard/running/create/" class="btn btn-default">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          New Running Order
        </a>
    </div><!-- /.col-lg-6 -->

<br>

<div class="row">

  <div class="col-md-12">
    <table class="table" id="no-more-tables">
      <tr>
        <td><h4>Black Coffe + Muffin</h4></td>
        <td>Sun Mon Tuse</td>
        <td>Deliver Home</td>
        <td>12 DHS</td>
        <td><a href="" class="btn btn-success">Running</a></td>
      </tr>
      <tr>
        <td><h4>Omeleete</h4></td>
        <td>Everyday</td>
        <td>Pickup</td>
        <td>12 DHS</td>
        <td><a href="" class="btn btn-danger">Stopped</a></td>
      </tr>
    </table>



  </div>

</div>



@stop

@section('footer')

@stop