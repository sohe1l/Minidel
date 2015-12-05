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


    <h2>Store Timings<br>
        <small>Customers will be able to make orders during the specified timings below.</small>
    </h2>

    @foreach ($workmodes as $workmode)

      <div class="panel panel-default">
          <div class="panel-heading clearfix">{{ $workmode->name }} Timings
            <span class="pull-right">
              <a href="javascript:deleteAll({{ $workmode->id }})">
                <span class="glyphicon glyphicon-remove" aria-hidden="true" title="Delete All"></span>
              </a>
              &nbsp;&nbsp;&nbsp;
              <a href="/manage/{{$store->slug}}/timings/workmode/{{ $workmode->id }}/create">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              </a>
            </span>


          </div>
          <table class="table">
            @forelse ($store->timings()->sortAsc()->where('workmode_id',$workmode->id)->get() as $timings)
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
              <tr><td>No timing added (closed all the time!)</td></tr>
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


      function deleteAll(workmodeid){
        var r = confirm("You are about to delete all the timings in the category. Are you sure?");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/timings/workmode/'+workmodeid+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }

  </script>

@endsection

