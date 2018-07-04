@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li class="active">Manage</li>
</ol>
@endsection

@section('content')




<h2>Manage Stores<br>
    <small>Here you can manage your stores or add new ones.</small>
</h2>

<?php /*
    <div class="row">
      <div class="col-lg-6">

      </div><!-- /.col-lg-6 -->

      <div class="col-lg-6" style="text-align: right">
        <a href="/manage/create/" class="btn btn-default">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          Create new Store
        </a>
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->
*/ ?>


@forelse ( $user->stores()->orderBy('name')->get() as $store)

        <div class="listing clearfix" style="border-bottom: 1px solid #e7e7e7; padding:1em 0;">
            <div class="col-sm-2">
                <img src="/img/logo/{{ $store->logo or 'placeholder.svg' }}" class="img-responsive hidden-xs">
                <img src="/img/cover/{{ $store->cover or 'placeholder.svg' }}" class="img-responsive visible-xs">
            </div>
            <div class="col-sm-10">
              <div class="clearfix">
              
                <h3 style="margin:0px 10px 0px 0;" class="pull-left">{{$store->name}}</h3>

                <div style="margin:5px 0; font-size:85%" class="pull-left">
                  @if($store->status_listing != 'published')
                    <span class="label label-warning">{{ $store->status_listing }}</span>
                  @endif

                  @if($store->status_working != 'open')
                    <span class="label label-danger">{{ $store->status_working }}</span>
                  @endif

                  @if($store->accept_orders != 1)
                    <span class="label label-info">Online Orders Closed</span>
                  @endif
                </div>
              </div>

              <div style="margin:5px 0; font-size:85%">
              {{ $store->city->name or '------' }} - {{ $store->area->name or '------' }} - {{ $store->building->name or '------' }}
              </div>


              <div style="font-size:1.1em">
                <a href="/manage/{{$store->slug}}">Incoming Orders</a> &nbsp;&nbsp;&nbsp;
                <a href="/manage/{{$store->slug}}/reports/">Reports</a> &nbsp;&nbsp;&nbsp;
                <a href="/manage/{{$store->slug}}/general/">Manage</a> &nbsp;&nbsp;&nbsp;
                <a href="/{{$store->slug}}/order/">View</a>
              </div>
            </div>
        </div>
@empty
    <div>You did not add any store yet. <a href="/manage/create">Add Store</a></div>
@endforelse





@endsection