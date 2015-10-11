@extends('layouts.default')

@section('content')

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li><a href="/manage/{{$store->slug}}/timings">Timings</a></li>
  <li class="active">New Timing</li>
</ol>

<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'timings'))
  </div>
  <div class="col-md-9">
        <h3>Create new timing record<br>
            <small>During the specified timing the customers can place orders.</small>
        </h3>


                    @include('errors.list')

                    {!! Form::open(array('url' => '/manage/'.$store->slug.'/timings', 'class'=>'form-horizontal' ) ) !!}

                        <div class="form-group">
                            <label class="col-md-4 control-label">Wrok Mode</label>
                            <div class="col-md-6">
                                {!! Form::select('workmode_id', $workmodes_list, null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Day</label>
                            <div class="col-md-6">
                                {!! Form::select('day', \Config::get('vars.days'), null, ['class'=>'form-control'] ) !!}

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Start Time</label>
                            <div class="col-md-6">
                                {!! Form::select('start', \Config::get('vars.timeList'), null, ['class'=>'form-control'] ) !!}

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">End Time</label>
                            <div class="col-md-6">
                                {!! Form::select('end', \Config::get('vars.timeList'), null, ['class'=>'form-control'] ) !!}

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="float:right">
                                    Create
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}

    </div>
</div>




@endsection

@section('footer')
@endsection