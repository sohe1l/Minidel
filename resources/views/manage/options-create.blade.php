@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

        <h2>Create new menu option <br>
            <small>Options will attach to your menu items and allow your customers to select different options such as sizes, topppings, and etc...</small>
        </h2>


            <div class="panel panel-default">
                <div class="panel-heading">Create new menu option</div>
                <div class="panel-body">

                    @include('errors.list')

                    <form class="form-horizontal" role="form" method="POST" action="/manage/{{ $store->slug }}/options">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Option Group Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Min selection</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="min" value="{{ old('min') }}">
                            </div>

                            <div class="col-md-4">
                                enter 0 if optional 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Max selection</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="max" value="{{ old('max') }}">
                            </div>

                            <div class="col-md-4">
                                enter 0 if optional 
                            </div>
                        </div>

                        <hr>



                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4"><b>Option Title</b></div>
                            <div class="col-md-2"><b>Extra Charge</b></div>
                        </div>

                        @for ($i = 1; $i<21; $i++)

                        <div class="form-group">
                            <label class="col-md-4 control-label">Option {{$i}}</label>
                            <div class="col-md-4">
                                <input type="text" placeholder="name" class="form-control" name="name{{$i}}" value="{{ old('name'.$i) }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" placeholder="price" class="form-control" name="price{{$i}}" value="{{ old('price'.$i) }}">
                            </div>
                        </div>

                        @endfor












                        <div style="text-align: right">
                            <small>The price of the option will be added to the menu item once selected.</small>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="float:right">
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
@endsection