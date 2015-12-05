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
  @include('manage.nav', array('active'=>'general'))
  </div>
  <div class="col-md-9">
    <h2>General Information<br>
        <small>Here you can view and edit information about your store such as location, logo, images and etc...</small>
    </h2>

    @include('errors.list') 



    <div class="panel panel-default">
        <div class="panel-heading">General Information</div>
        <div class="panel-body">

            {!! Form::model($store, array('class'=>'form-horizontal', 'url' => '/manage/'.$store->slug.'/general' )) !!}
            
            <div class="form-group">
                <label class="col-md-4 control-label">Name</label>
                <div class="col-md-8">
                    {!! Form::text('name' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Store Phone</label>
                <div class="col-md-8">
                {!! Form::text('phone' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Store Email</label>
                <div class="col-md-8">
                {!! Form::text('email' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Info</label>
                <div class="col-md-8">
                    {!! Form::textarea('info' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Store Type</label>
                <div class="col-md-8">
                    {!! Form::text('type', null, $attributes=array('class'=>'form-control', 'disabled') ) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Store URL</label>
                <div class="col-md-8">
                    {!! Form::text('storeslug' , "www.minidel.com/$store->slug", $attributes=array('class'=>'form-control', 'disabled') ) !!}
                </div>
            </div>

            @if($store->chain)
            <div class="form-group">
                <label class="col-md-4 control-label">Chain</label>
                <div class="col-md-8">
                    {!! Form::text('chain' , $store->chain->name, $attributes=array('class'=>'form-control', 'disabled') ) !!}
                </div>
            </div>
            @endif

            <div class="form-group clearfix">
                <label class="col-md-4 control-label">Accept Orders</label>
                <div class="col-md-8">
                        <label class="radio-inline">
                            {!! Form::radio('accept_orders', '1') !!} Yes
                        </label>
                        <label class="radio-inline">
                          {!! Form::radio('accept_orders', '0') !!} No
                        </label>
                </div>
            </div>

            <div class="form-group clearfix">
                <label class="col-md-4 control-label">Current Status</label>
                <div class="col-md-8">
                        <label class="radio-inline">
                            {!! Form::radio('status_working', 'open') !!} Open
                        </label>
                        <label class="radio-inline">
                          {!! Form::radio('status_working', 'close') !!} Close
                        </label>
                        <label class="radio-inline">
                          {!! Form::radio('status_working', 'busy') !!} Busy
                        </label>
                </div>
            </div>

            
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    {!! Form::submit('Update Information!', $attributes=array('class'=>'btn btn-primary')); !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">Logo Image</div>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-6 col-md-offset-3" style="text-align: center; padding-bottom:20px;">
                    <img src="/img/logo/{{ $store->logo or 'placeholder.svg' }}" class="img-thumbnail">
                </div>
            </div>

            {!! Form::open(array('files' => true, 'class'=>'form-horizontal', 'url' => '/manage/'.$store->slug.'/logo' )) !!}
                <div class="form-group">
                    <label class="col-md-4 control-label">Update by Upload</label>
                    <div class="col-md-4">
                        {!! Form::file('imagefile', $attributes=array('class'=>'')); !!}
                    </div>
                    <div class="col-md-2">
                        <input name="file_type" type="hidden" value="upload">
                        {!! Form::submit('Upload!', $attributes=array('class'=>'btn btn-sm btn-primary')); !!}
                    </div>
                </div>
            {!! Form::close() !!}

            {!! Form::open(array('class'=>'form-horizontal', 'url' => '/manage/'.$store->slug.'/logo' )) !!}
                <div class="form-group">
                    <label class="col-md-4 control-label">Update by URL</label>
                    <div class="col-md-4">
                        {!! Form::text('imgurl', null, $attributes=array('class'=>'')); !!}
                    </div>
                    <div class="col-md-2">
                        <input name="file_type" type="hidden" value="fetch">
                        {!! Form::submit('Fetch!', $attributes=array('class'=>'btn btn-sm btn-primary')); !!}
                    </div>
                </div>
            {!! Form::close() !!}


        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">Cover Image</div>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-6 col-md-offset-3" style="padding-bottom:20px;">
                    <img src="/img/cover/{{ $store->cover or 'placeholder.svg' }}" class="img-thumbnail">
                </div>
            </div>

        {!! Form::open(array('files' => true, 'class'=>'form-horizontal', 'url' => '/manage/'.$store->slug.'/cover' )) !!}
            <div class="form-group">
                <label class="col-md-4 control-label">Update by Upload</label>
                <div class="col-md-4">
                    {!! Form::file('imagefile', $attributes=array('class'=>'')); !!}
                </div>
                <div class="col-md-2">
                    <input name="file_type" type="hidden" value="upload">
                    {!! Form::submit('Upload!', $attributes=array('class'=>'btn btn-sm btn-primary')); !!}
                </div>
            </div>
        {!! Form::close() !!}

        {!! Form::open(array('class'=>'form-horizontal', 'url' => '/manage/'.$store->slug.'/cover' )) !!}
            <div class="form-group">
                <label class="col-md-4 control-label">Update by URL</label>
                <div class="col-md-4">
                    {!! Form::text('imgurl', null, $attributes=array('class'=>'')); !!}
                </div>
                <div class="col-md-2">
                    <input name="file_type" type="hidden" value="fetch">
                    {!! Form::submit('Fetch!', $attributes=array('class'=>'btn btn-sm btn-primary')); !!}
                </div>
            </div>
        {!! Form::close() !!}

        </div>
    </div>

  </div>
</div>












@endsection




@section('footer')
@endsection