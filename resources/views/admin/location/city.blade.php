@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/superadmin/">Super Admin</a></li>
    <li><a href="/superadmin/locations/">Locations</a></li>
    <li><a href="/superadmin/locations/{{ $city->country }}">{{ strtoupper($city->country) }}</a></li>
    <li class="active">{{ $city->name }}</li>
  </ol>
@endsection

@section('content')
    <h1>Locations</h1>
    <h2>{{ strtoupper($city->country) }} / {{ $city->name }}</h2>
    <div>Please select a area below to view the buildings:</div> <br>

        @foreach ($city->areas as $area)
            <a href="/superadmin/locations/{{$city->country}}/{{$city->slug}}/{{ $area->slug }}" class="btn btn-default btn-lg">{{ $area->name }}</a>    
        @endforeach

    <hr>
    <h3>Add a new area</h3>

    @include('errors.list')

    <form class="form-horizontal" role="form" method="POST" action="/superadmin/locations/{{$city->country}}/{{$city->slug}}/area">
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