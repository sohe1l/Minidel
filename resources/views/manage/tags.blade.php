@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Tags</li>
</ol>
@endsection

@section('head')
<style type="text/css">
  input[type=checkbox]:checked + label {
    color: #fda514;
  } 
</style>
@endsection

@section('content')




<div class="row">
  <div class="col-md-3">@include('manage.nav', array('active'=>'tags'))</div>
  <div class="col-md-9">
      
    <h2>Store Tags<br>
        <small>Tags will allow you to make your store to appear in searches. You can choose your store cusine, features, and etc...</small>
    </h2>

    @include('errors.list')

    {!! Form::open() !!}
  
    @foreach (\App\Tag::groupBy('type')->where('store_type',$store->type)->orderBy('type', 'asc')->get() as $type)
        <h4 style="text-transform: capitalize;"> {{ $type->type }} </h4>
        <div class="row" style="line-height: 3em;">
          @foreach (\App\Tag::where('type',$type->type)->orderBy('name', 'asc')->get() as $tag)
              <div class="col-sm06 col-md-3">
                {!! Form::checkbox('tags[]', $tag->id, in_array($tag->id, $tagslist) ? true : false, ['id'=>'tag'.$tag->id] ); !!}
                <label for="tag{{$tag->id}}">{{ $tag->name }}</label>
              </div>
          @endforeach
        </div>
    @endforeach

    <div style="text-align: right"><button type="submit" class="btn btn-default">Save Tags</button></div>

    {!! Form::close() !!}















  </div>

</div>


<div id="insert"></div>



@endsection

@section('footer')


@endsection



