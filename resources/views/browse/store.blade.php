@extends('layouts.default')

@section('content')


<h2 style="margin-top: 0;">{{ $store->name }} 
    <small id="headingSmall">
    <span id="storePhone" style="padding-top: 8px;"><span class="glyphicon glyphicon-phone-alt"></span> {{ $store->phone }}</span>
    {{ $store->building->name }} - {{ $area->name }}
    </small>
</h2>

<div class="row">

    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
        <div style="position: relative; margin-bottom: 20px;">
            <img src="/img/cover/{{ $store->cover}}" class="img-responsive" style="border-radius: 3px;">
            <img src="/img/logo/{{ $store->logo}}" class="img-thumbnail" style="position: absolute; bottom:10px; left:10px; max-width:20%;">
        </div>

        

      <div class="row">
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
        <div class="col-xs-6 col-md-3">
          <a href="#" class="thumbnail"><img src="/img/logo/placeholder.svg" alt="..."></a>
        </div>
    </div>





    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"> 
      
          <h4>About</h4>
            <div>{{ $store->info }}</div>
            


            @foreach ($store->tags as $tag)
              <span class="label label-default">{{ $tag->name }}</span>
              
            @endforeach

          <br><br>  
          <h4>Latest reviews</h4>
            <div>- Chicken Soup</div>
            <div>- Tikka Masala</div>
            <div>- Biryani</div>


            <br><br>
            <div>
              <h4>Timings</h4>
                <b>Room Service Timings</b>
                @foreach ($store->timings()->sortByDay()->where('workmode_id',1)->get() as $timings)
                    <div {!! (strtolower(date('D'))==$timings->day)?' style="font-weight:bold"':'' !!}>
                      {{ \Config::get('vars.days')[$timings->day] }}:
                      {{ $timings->start }} to {{ $timings->end }}
                    </div>
                @endforeach

                <br>

                <b>Delivery Timings</b>
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

            <br><br>
            <div>
              <h4>Latest reviews</h4>
                @forelse ($store->ratings()->latest()->limit(2)->get() as $rating)
                    <div style="font-size: 1.3em">
                        {{ $rating->user->name }} 
                        <small>{{ \Carbon\Carbon::parse($rating->created_at)->diffForHumans() }}</small>
                    </div>
                    

                    <div>{!! returnRating($rating->rating) !!}</div>
                    <div>{{ $rating->public_review }}</div>

                    
                    <hr>
                @empty
                    <i>No reviews yet for this store. Write the first review...</i>
                @endforelse

                <div style="text-align: right"><a href="reviews">Read More</a></div>
            </div>



    </div>

</div>





@endsection
@section('footer')

@endsection