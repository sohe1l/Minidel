@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <h2>New Coverage Area<br>
                <small>By addding an area, automatically all the buildings in that area will be included.</small>
            </h2>

            @include('errors.list')

            <div class="panel panel-default">
                <div class="panel-heading">Location</div>
                <div class="panel-body">
                    {!! Form::open(array('url' => '/manage/'.$store->slug.'/coverage/area', 'class'=>'form-horizontal' ) ) !!}


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

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            {!! Form::submit('Add Area!', $attributes=array('class'=>'btn btn-primary')); !!}
                        </div>
                    </div>
                {!! Form::close() !!}
                </div>
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


        function helper_clear(input){
            $(input).select2('val','');
            $(input).html(' ');
        }

        $('#country').select2().on("change", update_city);
        $('#city').select2().on("change", update_area);
        $('#area').select2().on();

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