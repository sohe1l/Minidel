@extends('layouts.default')


@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li><a href="/browse/">Browse</a></li>
    <li><a href="/browse/{{$store->city->slug}}">{{$store->city->name}}</a></li>
    <li><a href="/browse/{{$store->city->slug}}/{{$store->area->slug}}">{{$store->area->name}}</a></li>
    @if ($store->building)
      <li><a href="/browse/{{$store->city->slug}}/{{$store->area->slug}}/{{ $store->building->slug }}">{{$store->building->name}}</a></li>
    @endif
    <li class="active">{{$store->name}}</li>
  </ol>
@endsection


@section('content')


<h2 style="margin-top: 15px !important;">{{ $store->name }} 
    <small id="headingSmall">
    {{ ($store->building)?$store->building->name:'' }} - {{ $store->area->name }}
    </small>
</h2>


<div class="row">

    <div class="col-xs-12 col-sm-7 col-md-8 col-lg-8">
        <div style="position: relative; margin-bottom: 20px;">
            <img src="/img/cover/{{ $store->cover}}" class="img-responsive" style="border-radius: 3px;">
            <img src="/img/logo/{{ $store->logo}}" class="img-thumbnail" style="position: absolute; bottom:10px; left:10px; max-width:20%;">
        </div>

      @include('errors.list')

      <h3>Menu Photos</h3>
      <div class="row">
      @foreach($store->photos->sortByDesc('created_at')->where('type','menu') as $photo)
        <div class="col-xs-4 col-sm-4 col-md-3 col-xl-3">
          <a href="/img/menu/{{$photo->path}}" class="thumbnail" data-lity><img src="/img/menu-thumb/{{$photo->path}}" alt="...">
            <div style="text-align: center; color:black; font-size: 80%">{{ $photo->text }}</div>
          </a>

        </div>
      @endforeach
      </div>

      <div>
        <h3>Photos
        <small v-if="!showAddPhoto"><a v-on:click="showAddPhoto = true"><span class="glyphicon glyphicon-plus"></span> Add</a></small>
        </h3>
      </div>

      <div class="panel panel-warning" v-if="showAddPhoto">
        <div class="panel-heading">Add a new Photo</div>
        <div class="panel-body">
          {!! Form::model($store, array('files' => true, 'class'=>'form-horizontal', 'url' => $store->slug.'/photo' )) !!}
            <div class="form-group">
              <label for="photoCaption" class="col-sm-2 control-label">Caption</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="photoCaption" name="photoCaption" placeholder="Write some caption"></textarea>
              </div>
            </div>

            <div class="form-group">
              <label for="imagefile" class="col-sm-2 control-label">Photo</label>
              <div class="col-sm-10">
                {!! Form::file('imagefile', $attributes=array('class'=>'form-control','id'=>'imagefile')); !!}
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit('Add photo!', $attributes=array('class'=>'btn btn-sm btn-primary')); !!}
              </div>
            </div>

          {!! Form::close() !!}
        </div>
      </div>

      <div class="row">
      @foreach($store->photos->sortByDesc('created_at')->where('type','general') as $photo)
        <div class="col-xs-4 col-sm-4 col-md-3 col-xl-3">
          <a href="/img/store/{{$photo->path}}" class="thumbnail" data-lity><img src="/img/store-thumb/{{$photo->path}}" alt="...">
            <div style="text-align: center; color:black; font-size: 80%">{{ $photo->text }}</div>
          </a>

        </div>
      @endforeach

      </div>





    </div>
    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4"> 

      <div class="row" style="margin-bottom: 15px; text-align: center">
        <div class="col-xs-6">
          <a class="btn btn-info" href="tel:{{ $store->phone }}">
            {{ $store->phone }} <span class="badge"><span class="glyphicon glyphicon-phone-alt"></span></span>
          </a>
        </div>
        <div class="col-xs-6">
          @if($store->accept_orders)
            <a class="btn btn-danger" href="/{{$store->slug}}/order">Order Online</a>
          @endif
        </div> 
      </div>

      <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title">About</h3></div>
        <div class="panel-body">
          <div>
            {{ $store->info }}
          </div>
          <div>
            @foreach ($store->tags as $tag)
              <span class="label label-info">{{ $tag->name }}</span>
            @endforeach
          </div>
          <div>
            @foreach ($store->payments as $payment)
              <span class="label label-info">{{ $payment->name }}</span>
            @endforeach
          </div>
        </div>
      </div>

      <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title">Location Map</h3></div>
        <div class="panel-body">
          <iframe width="100%" height="150" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyD4ErY960Th0MmUL2qD1uZiYPRetpEqqsc&q={{$store->coordinate}}" allowfullscreen></iframe>
        </div>
      </div>
          
      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Latest Review 
        <small><a href="/{{$store->slug}}/reviews">Show All</a></small></h3>
      </div>
        <div class="panel-body">
            @forelse ($store->ratings()->latest()->take(1)->get() as $rating)
                <div style="font-size: 1.1em">
                    {{ $rating->user->name }} 
                    <small>{{ \Carbon\Carbon::parse($rating->created_at)->diffForHumans() }}</small>
                </div>
                <div>{!! returnRating($rating->rating) !!}</div>
                <div>{{ $rating->public_review }}</div>
            @empty
                <i>No reviews yet for this store. Write the first review...</i>
            @endforelse
        </div>
      </div>
          
      <div class="panel panel-default">
      <div class="panel-heading"><h3 class="panel-title">Timings</h3></div>
        <div class="panel-body">

                <b>Building Delivery Timings</b>
                @foreach ($store->timings()->sortByDay()->where('workmode_id',1)->get() as $timings)
                    <div {!! (strtolower(date('D'))==$timings->day)?' style="font-weight:bold"':'' !!}>
                      {{ \Config::get('vars.days')[$timings->day] }}:
                      {{ $timings->start }} to {{ $timings->end }}
                    </div>
                @endforeach

                <br>

                <b>Area Delivery Timings</b>
                @foreach ($store->timings()->sortByDay()->where('workmode_id',2)->get() as $timings)
                    <div {!! (strtolower(date('D'))==$timings->day)?' style="font-weight:bold"':'' !!}>
                      {{ \Config::get('vars.days')[$timings->day] }}:
                      {{ $timings->start }} to {{ $timings->end }}
                    </div>
                @endforeach

                <br>

                <b>Pickup Timings</b>
                @foreach ($store->timings()->sortByDay()->where('workmode_id',5)->get() as $timings)
                    <div {!! (strtolower(date('D'))==$timings->day)?' style="font-weight:bold"':'' !!}>
                      {{ \Config::get('vars.days')[$timings->day] }}:
                      {{ $timings->start }} to {{ $timings->end }}
                    </div>
                @endforeach

        </div>
      </div>
          


    </div>

</div>





@endsection
@section('footer')
<script type="text/javascript">
    var vm = new Vue({
    el: '#defaultMainContainer',
    data:{
        showAddPhoto : false,
    },
  })
</script>
@endsection
