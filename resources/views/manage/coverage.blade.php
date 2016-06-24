@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Coverage</li>
</ol>
@endsection

@section('content')




<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'coverage'))
  </div>
  <div class="col-md-9">
      
        <h2>Delivery Coverage<br>
            <small>Delivery Coverage can be defined either by areas or by individual buildings.</small>
        </h2>


        <div class="panel panel-default">
          <div class="panel-heading">Coverage Areas
            <a href="/manage/{{$store->slug}}/coverage/area/create" style="float:right" class="btn btn-xs btn-default">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New
            </a>
          </div>
          <table class="table">
          @forelse ($store->coverageAreas as $area)
            <tr>
              <td><b>{{ $area->name }}</b></td>
              <td>Min: {{$area->pivot->min}}</td>
              <td>Fee: {{$area->pivot->fee}}</td>
              <td>Below Min Fee: {{$area->pivot->feebelowmin}}</td>
<!--               <td>Discount: {{$area->pivot->discount}}</td>
 -->              <td>
                <a href="javascript:deleteArea({{ $area->id }})" title="Delete">
                  <span class="glyphicon glyphicon-remove"></span>
                </a>
              </td>
            </tr>
          @empty
            <tr><td>No areas added!</td></tr>
          @endforelse
          </table>
        </div>



        <div class="panel panel-default">
          <div class="panel-heading">Coverage Buildings
            <a href="/manage/{{$store->slug}}/coverage/building/create" style="float:right" class="btn btn-xs btn-default">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New
            </a>
          </div>
          <table class="table">
          @forelse ($store->coverageBuildings as $building)
            <tr>
              <td><b>{{ $building->name }}</b></td>
              <td>Min Delivery: {{$building->pivot->min}}</td>
              <td>Delivery Fee: {{$building->pivot->fee}}</td>
              <td>Below Min Fee: {{$building->pivot->feebelowmin}}</td>
<!--               <td>Discount: {{$building->pivot->discount}}</td>
 -->              <td>
                <a href="javascript:deleteBuilding({{ $building->id }})" title="Delete">
                  <span class="glyphicon glyphicon-remove"></span>
                </a>
              </td>
            </tr>
          @empty
            <tr><td>No buildings added!</td></tr>
          @endforelse
          </table>
        </div>















  </div>

</div>






<div id="insert">
</div>



@endsection

@section('footer')
  <script type="text/javascript">
      function deleteArea(area_id){
        var r = confirm("Are you sure you want to delete the area?");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/coverage/area/'+area_id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }

      function deleteBuilding(id){
        var r = confirm("Are you sure you want to delete the building?");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/coverage/building/'+id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }
  </script>

@endsection


