@extends('layouts.admin')

@section('content')
    <h1>Locations</h1>
    <h2>Country: {{ strtoupper($countrySlug) }}</h2>
    <div>Please select a city below to view the areas:</div>

        @foreach ($cities as $city)
            <a href="/superadmin/location/{{$countrySlug}}/{{$city->slug}}" class="btn btn-default btn-lg">
            {{ $city->name }}</a>    
        @endforeach

    <hr>
    <h3>Add a new city</h3>

    @include('errors.list')

    <form class="form-horizontal" role="form" method="POST" action="/superadmin/location/{{$countrySlug}}/city/">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="country" value="{{ $countrySlug }}">

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
            <label class="col-md-4 control-label">Area Code</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="areacode" value="{{ old('areacode') }}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Register
                </button>
            </div>
        </div>
    </form>

@stop