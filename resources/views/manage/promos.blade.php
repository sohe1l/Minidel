@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">General</li>
</ol>
@endsection

@section('content')

<style>
     .controls {
        background-color: #fff;
        border-radius: 2px;
        border: 1px solid transparent;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        box-sizing: border-box;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        height: 29px;
        margin-left: 17px;
        margin-top: 10px;
        outline: none;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
      }

      .controls:focus {
        border-color: #4d90fe;
      }
</style>




<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'promos'))
  </div>
  <div class="col-md-9">
    <h2>Promos<br>
        <small>Here you can manage promotions for your stores. You can have 1 active promotion at any time.</small>
    </h2>

    @include('errors.list') 

    @if(!$active_promos)

    <div class="panel panel-default">
        <div class="panel-heading">Create a new PromotionG</div>
        <div class="panel-body">

            {!! Form::open(array('class'=>'form-horizontal', 'url' => '/manage/'.$store->slug.'/promos' )) !!}
            
            <div class="form-group">
                <label class="col-md-4 control-label">Promotion Name <br><small>for your refrence only</small></label>
                <div class="col-md-8">
                {!! Form::text('name' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Type</label>
                <div class="col-md-8">
                    <p class="form-control-static">Discount Percent</p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Discount Percent</label>
                <div class="col-md-8">
                {!! Form::text('discount_percent' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Promotion Text
                <br><small>will be shown to customers</small></label>
                <div class="col-md-8">
                {!! Form::text('text' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>




            <div class="form-group">
                <label class="col-md-4 control-label">Start Date</label>
                <div class="col-md-8">
                {!! Form::date('start_date' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Duration</label>
                <div class="col-md-8">
                    <p class="form-control-static">1 Week</p>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    {!! Form::submit('Create Promotion!', $attributes=array('class'=>'btn btn-primary')); !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    @endif


    <div class="panel panel-default">
        <table class="table table-striped">
            <tr>
                <td>Name</td>
                <td>Type</td>
                <td>Value</td>
                <td>Text</td>
                <td>Start Date</td>
                <td>End Date</td>
                <td></td>
            </tr>
        @foreach($store->promos as $promo)
            <tr>
                <td>{{$promo->name}}</td>
                <td>{{$promo->type}}</td>
                <td>{{$promo->value}}</td>
                <td>{{$promo->text}}</td>
                <td>{{$promo->start_date}}</td>
                <td>{{$promo->end_date}}</td>
                <td><a href="javascript:deleteItem({{$promo->id}})"><i class="glyphicon glyphicon-trash"></i></a></td>
            </tr>


        @endforeach
        </table>
    </div>



  </div>
</div>






<div id="insert">
</div>

@endsection



@section('footer')
  <script type="text/javascript">

      function deleteItem(item_id){
        var r = confirm("Are you sure you want to delete this promotion ?");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/promos/'+item_id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }
  </script>
@endsection