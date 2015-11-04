@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Options</li>
</ol>
@endsection

@section('content')




<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'options'))
  </div>
  <div class="col-md-9">
      
    <h2>Menu Options <br>
        <small>Options will attach to your menu items and allow your customers to select different options such as sizes, topppings, and etc...</small>
    </h2>

    @include('errors.list')

    <div class="row">
      <div class="col-lg-4">
      
      </div><!-- /.col-lg-6 -->
      <div class="col-lg-4">

      </div>
      <div class="col-lg-4" style="text-align:right">
        <a href="/manage/{{$store->slug}}/options/create" class="btn btn-default">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          New Menu Option
        </a>
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->

    <br>
 
    @foreach ($store->options as $option)


      <div class="panel panel-default">
          <div class="panel-heading">
            <b>{{ $option->name }}</b> [min: {{ $option->min }} max: {{ $option->max }}]
            <a href="javascript:deleteOption({{ $option->id }})">
              <span class="glyphicon glyphicon-remove" style="float:right"></span>
            </a>
            <span style="float:right">&nbsp;&nbsp;&nbsp;</span>
            <a href="/manage/{{$store->slug}}/options/{{ $option->id }}/edit">
              <span class="glyphicon glyphicon-pencil" style="float:right"></span>
            </a>
          </div>
          
          <table class="table">
          @foreach ($option->options as $item)
            <tr>
              <td>

                @if($item->available)
                  <a href="javascript:available({{ $item->id }},0)" title="Currenly Available. Click to mark as unavailable.">
                    <span style="color:#93C942" class="glyphicon glyphicon-ok-sign"></span>
                  </a>
                @else
                  <a href="javascript:available({{ $item->id }},1)" title="Currenly Unavailable. Click to mark as available.">
                    <span style="color:#C94242" class="glyphicon glyphicon-info-sign"></span>
                  </a>
                @endif

                {{ $item->name }}

              </td>
              <td>{{ $item->price }}</td>
            </tr>
          @endforeach

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
      function deleteOption(option_id){
        var r = confirm("Deleting an option will delete all the items in it as well. Please confirm.");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/options/'+option_id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }

      function available(item_id,isAvailable){
        if(isAvailable == 1) var mes = "Are you sure you want to mark this item as available?";
        if(isAvailable == 0) var mes = "Are you sure you want to mark this item as unavailable?"; 
        var r = confirm(mes);
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/options/'+item_id+'/available" method="post"><input type="hidden" name="_method" value="PUT" /><input type="hidden" name="available" value="'+isAvailable+'" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }

  </script>

@endsection



