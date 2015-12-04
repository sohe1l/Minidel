@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Zomato</li>
</ol>
@endsection

@section('content')


<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'zomato'))
  </div>
  <div class="col-md-9">
    <h2>Zomato Import<br>
        <small>Only to be done on empty store.</small>
    </h2>

    @include('errors.list') 

    {!! Form::open(['id'=>'zomato', 'class'=>'form-horizontal', 'url' => '/manage/'.$store->slug.'/zomato/process/']) !!}
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Zomato URL</span>
          <input type="text" class="form-control" placeholder="" name="url">
          <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">Process!</button>
          </span>
        </div><!-- /input-group -->
    {!! form::close() !!}

  </div>
</div>



@endsection

@section('footer')
@endsection