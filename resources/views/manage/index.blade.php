@extends('layouts.default')

@section('content')


<h2>Manage Stores<br>
    <small>Here you can manage your stores or add new ones.</small>
</h2>


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



@forelse ( $user->stores as $store)

        <div class="listing clearfix" style="border-bottom: 1px solid #e7e7e7; padding:1em 0;">
            <div class="col-sm-2">
                <img src="/img/logo/{{ $store->logo or 'placeholder.svg' }}" class="img-responsive hidden-xs">
                <img src="/img/cover/{{ $store->cover or 'placeholder.svg' }}" class="img-responsive visible-xs">
            </div>
            <div class="col-sm-10">
              <div><h3>{{$store->name}}</h3></div>
              <div><a href="/manage/{{$store->slug}}">Orders</a></div>
              <div><a href="/manage/{{$store->slug}}/general/">Reports</a></div>
              <div><a href="/manage/{{$store->slug}}/general/">Manage</a></div>
            </div>
        </div>
@empty
    <div>You did not add any store yet. <a href="/manage/create">Add Store</a></div>
@endforelse

@endsection