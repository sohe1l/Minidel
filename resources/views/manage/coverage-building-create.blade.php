@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li><a href="/manage/{{$store->slug}}/coverage">Coverage</a></li>
  <li class="active">New Building</li>
</ol>
@endsection

@section('content')




<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'coverage'))
  </div>
  <div class="col-md-9">
    <h3>New Coverage Building<br> <small>Addding an building will override area min delivery and delivery fees.</small></h3>



            @include('errors.list')
                    {!! Form::open(array('url' => '/manage/'.$store->slug.'/coverage/building', 'class'=>'form-horizontal' ) ) !!}


                    <div class="form-group">
                        <label class="col-md-4 control-label">Country</label>
                        <div class="col-md-6">
                            {!! Form::select('country', $countries, 'AE', $attributes=array('class'=>'form-control','id'=>'country') );  !!}
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
                        <label class="col-md-4 control-label"></label>
                        <div class="col-md-6">
                            <b>Delivery for buildings have no minimum delivery and no delivery charge</b>
                        </div>
                    </div>

<?php /*
                    <div class="form-group">
                        <label class="col-md-4 control-label">Discount %</label>
                        <div class="col-md-6">
                            {!! Form::select('discount', \Config::get('vars.discounts'), 0, $attributes=array('class'=>'form-control')); !!}
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-md-4 control-label">Min Order</label>
                        <div class="col-md-6">
                            {!! Form::text('min', 0, $attributes=array('class'=>'form-control')); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Delivery Fee</label>
                        <div class="col-md-6">
                            {!! Form::text('fee', 0, $attributes=array('class'=>'form-control')); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Accept orders below minimum delivery</label>
                        <div class="col-md-6">
                            <input type="checkbox" id="checkAccept"> Yes
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Extra Charge for orders below the minimum delivery</label>
                        <div class="col-md-6">
                            {!! Form::text('feebelowmin', 0, $attributes=array('class'=>'form-control', 'id'=>'textAccept', 'readonly')); !!}
                        </div>
                    </div>
*/ ?>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            {!! Form::submit('Add Area!', $attributes=array('class'=>'btn btn-primary')); !!}
                        </div>
                    </div>
                {!! Form::close() !!}

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

        $(document).ready(function() {
            $('#checkAccept').change(function() {
                $("#textAccept").val(0);
                if($(this).is(":checked")) {
                    $("#textAccept").prop('readonly',false);
                }else{
                    $("#textAccept").prop('readonly',true);
                }
            });
        });

    </script>
@endsection