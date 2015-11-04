@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Menu Photos</li>
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
  @include('manage.nav', array('active'=>'menu-photo'))
  </div>
  <div class="col-md-9">
    <h2>Menu Photos<br>
        <small>Here you can view and edit your menu photos.</small>
    </h2>

    @include('errors.list') 

    <div class="panel panel-default">
    <div class="panel-heading">Add a new Photo</div>
    <div class="panel-body">
      {!! Form::model($store, array('files'=>true, 'class'=>'form-horizontal', 'url'=>'/manage/'.$store->slug.'/menu/photos')) !!}
        <div class="form-group">
          <label for="photoCaption" class="col-sm-2 control-label">Caption</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="photoCaption" name="photoCaption" placeholder="Write some caption"></textarea>
          </div>
        </div>

        <div class="form-group">
          <label for="imagefile" class="col-sm-2 control-label">Photo</label>
          <div class="col-sm-10">
            {!! Form::file('imagefile', $attributes=array('class'=>'form-control','id'=>'imagefile')); !!}
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            {!! Form::submit('Add photo!', $attributes=array('class'=>'btn btn-sm btn-primary')); !!}
          </div>
        </div>

      {!! Form::close() !!}
    </div>
    </div>

    <table class="table">
      @foreach($store->photos->sortBy('order')->where('type','menu') as $photo)
        <tr>

          <td style="width:100px;"><a href="/img/menu/{{$photo->path}}" data-lity><img src="/img/menu-thumb/{{$photo->path}}"></a></td>
          <td>{{ $photo->text }}</td>
          <td style="width:100px;">

            <a href="/manage/{{$store->slug}}/menu/photo/{{ $photo->id }}/edit" title="Edit">
              <span class="glyphicon glyphicon-pencil"></span>
            </a>

            &nbsp;&nbsp;&nbsp;

            <a href="/manage/{{$store->slug}}/menu/photo/{{ $photo->id }}/up" title="Move up">
              <span class="glyphicon glyphicon-arrow-up"></span>
            </a>

            <br><br>

            <a href="javascript:deleteItem({{ $photo->id }})" title="Delete">
              <span class="glyphicon glyphicon-remove"></span>
            </a>

            &nbsp;&nbsp;&nbsp;

            <a href="/manage/{{$store->slug}}/menu/photo/{{ $photo->id }}/down" title="Move down">
              <span class="glyphicon glyphicon-arrow-down"></span>
            </a>

          </td>
        </tr>
      @endforeach
    </table>

  </div>
</div>










<div id="insert"></div>
@endsection




@section('footer')
  <script type="text/javascript">

      function deleteItem(item_id){
        var r = confirm("Are you sure you want to delete this item ?");
        if (r == true) {
          $('#insert').html('<form action="/manage/{{$store->slug}}/menu/photo/'+item_id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form").submit();
        }
      }

  </script>
@endsection