@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li class="active">New Store</li>
</ol>
@endsection

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

                        <h4>General</h4>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Store Type</label>
                            <div class="col-md-6">
                                {!! Form::select('type', getEnumValues('stores','type'),'food',['class'=>'form-control']) !!}
                            </div>
                        </div>


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

                        <h4>Store Url</h4>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Store Url</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="store_url" value="{{ old('store_url') }}">
                            </div>
                        </div>
                        <div>
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-8">
                                Store url will be www.minidel.com/store-url
                            </div>
                        </div>


<?php /*
                        <h4>Payment</h4>

                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-6">
                                Zero setup fee.<br>Zero monthly fee.<br>10% comission as per orders.
                            </div>
                        </div>

*/ ?>


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
        @include('layouts.partials.location-select')
        update_city();
    </script>
@endsection