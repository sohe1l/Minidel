@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Timings</li>
</ol>
@endsection

@section('content')



<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'timings'))
  </div>
  <div class="col-md-9">


    <h2>Delivery Timings<br>
        <small>Customers will be able to make orders during the specified timings below.</small>
    </h2>

    <div class="row">
      <div class="col-lg-6">

      </div><!-- /.col-lg-6 -->

      <div class="col-lg-6" style="text-align:right">
        <a href="/manage/{{$store->slug}}/timings/create" class="btn btn-default">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          New Timing
        </a>
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->

    <br>

    @foreach ($workmodes as $workmode)

      <div class="panel panel-default">
          <div class="panel-heading">{{ $workmode->name }} Timings</div>
          <table class="table">
            @forelse ($store->timings()->sortByDay()->where('workmode_id',$workmode->id)->get() as $timings)
              <tr>
                <td><b>{{ \Config::get('vars.days')[$timings->day] }}</b></td>
                <td>{{ $timings->start }} &nbsp;&nbsp;&nbsp; to &nbsp;&nbsp;&nbsp; {{ $timings->end }}</td>

                <td style="width:50px">
                  <a href="javascript:deleteTiming({{ $timings->id }})" title="Delete">
                    <span class="glyphicon glyphicon-remove"></span>
                  </a>
                </td>
              </tr>
            @empty
              <tr><td>No areas added!</td></tr>
            @endforelse
            </table>
      </div>



    @endforeach


  </div>

</div>




<div id="insert">
</div>



@endsection

@section('footer')
  <script type="text/javascript">
      function deleteTiming(id){
        var r = confirm("You are about to delete a timing. Are you sure?");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/timings/'+id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }
  </script>

@endsection

