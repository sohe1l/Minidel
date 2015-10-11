@extends('layouts.default')

@section('content')

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">General</li>
</ol>


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
                <label class="col-md-4 control-label">Status</label>
                <div class="col-md-6">
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
                <label class="col-md-4 control-label">Name</label>
                <div class="col-md-6">
                    {!! Form::text('name' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Store Phone</label>
                <div class="col-md-6">
                {!! Form::text('phone' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Store Email</label>
                <div class="col-md-6">
                {!! Form::text('email' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Info</label>
                <div class="col-md-6">
                    {!! Form::textarea('info' , $value = null, $attributes=array('class'=>'form-control') ) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    {!! Form::submit('Update Information!', $attributes=array('class'=>'btn btn-primary')); !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">Logo Image</div>
        <div class="panel-body">

            {!! Form::model($store, array('files' => true, 'class'=>'form-horizontal', 'url' => '/manage/'.$store->slug.'/logo' )) !!}
            <div class="form-group">
                <label class="col-md-4 control-label">Current Logo</label>
                <div class="col-md-6">
                    <img src="/img/logo/{{ $store->logo or 'placeholder.svg' }}" class="img-thumbnail">
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Update Cover</label>
                <div class="col-md-4">
                    {!! Form::file('imagefile', $attributes=array('class'=>'')); !!}
                </div>
                <div class="col-md-2">
                    {!! Form::submit('Update Logo!', $attributes=array('class'=>'btn btn-sm btn-primary')); !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">Cover Image</div>
        <div class="panel-body">
            {!! Form::model($store, array('files' => true, 'class'=>'form-horizontal', 'url' => '/manage/'.$store->slug.'/cover' )) !!}
            <div class="form-group">
                <label class="col-md-4 control-label">Current Cover</label>
                <div class="col-md-6">
                    <img src="/img/cover/{{ $store->cover or 'placeholder.svg' }}" class="img-thumbnail">
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Update Cover</label>
                <div class="col-md-4">
                    {!! Form::file('imagefile', $attributes=array('class'=>'')); !!}
                </div>
                <div class="col-md-2">
                    {!! Form::submit('Update Cover!', $attributes=array('class'=>'btn btn-sm btn-primary')); !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>



    <div class="panel panel-default">
        <div class="panel-heading">Location</div>
        <div class="panel-body">
            {!! Form::model($store, array('url' => '/manage/'.$store->slug.'/location', 'class'=>'form-horizontal' ) ) !!}
            <div>
                <b>Current Location:</b>
                {{ $store->country }} / 
                {{ $store->city->name or '-' }} /
                {{ $store->area->name or '-' }} /
                {{ $store->building->name or '-' }} 
            </div>

            <br>


            <div class="form-group">
                <label class="col-md-4 control-label">Country</label>
                <div class="col-md-6">
                    {!! Form::select('country', $countries, null, $attributes=array('class'=>'form-control','id'=>'country') );  !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">City</label>
                <div class="col-md-6">
                    {!! Form::select('city_id',[],null,$attributes=array('class'=>'form-control','id'=>'city') ); !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Area</label>
                <div class="col-md-6">
                    {!! Form::select('area_id',[],null,$attributes=array('class'=>'form-control','id'=>'area') ); !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Building</label>
                <div class="col-md-6">
                    {!! Form::select('building_id',[],null,$attributes=array('class'=>'form-control','id'=>'building') ); !!}
                </div>
            </div>



            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    {!! Form::submit('Update Location!', $attributes=array('class'=>'btn btn-primary')); !!}
                </div>
            </div>
        {!! Form::close() !!}
        </div>
    </div>



  </div>
</div>












@endsection




@section('footer')
    <script type="text/javascript">

        function update_city(){
            $.ajax({
              url: '/api/v1/country/'+ $("#country").val() +'/cities/',
              type: 'GET',
              cache: true,
              success: function(data) {
                if(data.length == 0){
                    $('#city').select2('val','');
                    $('#city').html('');
                }else{
                    $('#city').select2({data: data});
                }
                update_area();
               },
              error: function(e) { alert("Some error occurred. Please try again."); }
            });
        }

        function update_area(){
            if($("#city").val() == null ){
                helper_clear('#area');
            }else{
                $.ajax({
                  url: '/api/v1/country/'+ $("#country").val() + '/cities/' + $("#city").val() + '/areas/' ,
                  type: 'GET',
                  cache: true,
                  success: function(data) {
                    if(data.length == 0){
                        helper_clear('#area');
                    }else{
                        $('#area').select2({data: data});
                    }
                    update_building();
                   },
                  error: function(e) { alert("Some error occurred. Please try again."); }
                });
            }
        }

        function update_building(){
            if($("#city").val() == null || $("#area").val() == null ){
                helper_clear('#building');
            }else{
                $.ajax({
                  url: '/api/v1/country/'+ $("#country").val() + '/cities/' + $("#city").val() + '/areas/' + $("#area").val() + '/buildings/' ,
                  type: 'GET',
                  cache: true,
                  success: function(data) { 
                    if(data.length == 0) {
                        helper_clear('#building');
                    }else{
                        $('#building').select2({data: data});
                    }
                  },
                  error: function(e) { alert("Some error occurred. Please try again."); }
                });
            }
        }

        function helper_clear(input){
            $(input).select2('val','');
            $(input).html(' ');
        }

        $('#country').select2().on("change", update_city);
        $('#city').select2().on("change", update_area);
        $('#area').select2().on("change", update_building);
        $('#building').select2();

        update_city();

    </script>
@endsection