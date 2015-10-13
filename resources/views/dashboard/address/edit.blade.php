@extends('layouts.default')

@section('content')

<ol class="breadcrumb hidden-xs">
  <li><a href="/">Home</a></li>
  <li><a href="/dashboard/">Dashboard</a></li>
  <li><a href="/dashboard/address">Address</a></li>
  <li class="active">Edit Address</li>
</ol>


<div class="row">
  <div class="col-md-3 hidden-xs">
  @include('dashboard.nav', array('active'=>'address'))
  </div>
  <div class="col-md-9">
            <h3>Edit Address<br>
                <small>Use the below form to edit your address easily.</small>
            </h3>

            @include('errors.list')


                    {!! Form::model($address, array('class'=>'form-horizontal',
                                                'method' => 'put',
                                                'url' => '/dashboard/address/'.$address->id ) ) !!}


                    <div class="form-group">
                        <label class="col-md-4 control-label">Country</label>
                        <div class="col-md-6">
                        {!! Form::select('country', ['AE'=>"UAE"], 'AE', $attributes=array('class'=>'form-control','id'=>'country') );  !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">City</label>
                        <div class="col-md-6">
                            {!! Form::select('city_id', \App\City::listByCountrySelect('AE') ,null,$attributes=array('class'=>'form-control','id'=>'city') ); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Area</label>
                        <div class="col-md-6">
                            {!! Form::select('area_id',\App\Area::listByCitySelect($address->city_id),null,$attributes=array('class'=>'form-control','id'=>'area') ); !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-4 control-label">Building</label>
                        <div class="col-md-6">
                            {!! Form::select('building_id',\App\Building::listByAreaSelect($address->area_id),null,$attributes=array('class'=>'form-control','id'=>'building') ); !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-4 control-label">Unit Number</label>
                        <div class="col-md-6">
                            {!! Form::text('unit', null, $attributes=array('class'=>'form-control')); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Extra Directions</label>
                        <div class="col-md-6">
                            {!! Form::text('info', null, $attributes=array('class'=>'form-control')); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Phone (optional)</label>
                        <div class="col-md-6">
                            {!! Form::text('phone', null, $attributes=array('class'=>'form-control')); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Address Name</label>
                        <div class="col-md-6">
                            {!! Form::text('name', null, $attributes=array('class'=>'form-control', 'placeholder'=>'Home, Office, ..')); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4" style="text-align: right">
                            {!! Form::submit('Edit Address!', $attributes=array('class'=>'btn btn-primary')); !!}
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

    </script>
@endsection