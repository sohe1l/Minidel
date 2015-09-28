@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create a New Store</div>
                <div class="panel-body">

                    @include('errors.list')


                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/manage/create') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label">Store Phone</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Store Email</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>




                        <h4>Location</h4>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Country</label>
                            <div class="col-md-6">
                                <select class="form-control" name="country" id="country">
                                    @foreach ($countries as $countryKey => $countryName)
                                        <option value="{{ $countryKey }}"  {{ ($countryKey=="AE")?"selected":"" }} >{{ $countryName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">City</label>
                            <div class="col-md-6">

                                <select class="form-control" name="city" id="city">
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Area</label>
                            <div class="col-md-6">
                                
                                <select class="form-control" name="area" id="area">
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Building</label>
                            <div class="col-md-6">
                                <select class="form-control" name="building" id="building">
                                </select>
                            </div>
                        </div>









                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
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