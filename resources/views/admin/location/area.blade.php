@extends('layouts.admin')

@section('content')
    <h1>Locations</h1>
    <h2>{{ strtoupper($city->country) }} / {{ $city->name }} / {{ $area->name }}</h2>
    <div>Building in the area:</div> <br>

        @foreach ($area->buildings as $building)
            <a class="btn btn-default btn-lg">{{ $building->name }}</a>    
        @endforeach

    <hr>
    <h3>Add a new building</h3>

    @include('errors.list')

    <form class="form-horizontal" role="form" method="POST" action="/superadmin/location/{{$city->country}}/{{$city->slug}}/{{$area->slug}}/building">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group">
            <label class="col-md-4 control-label">Name</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Slug</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="slug" value="{{ old('slug') }}">
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

@stop