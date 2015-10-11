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


        <h3>Reviews & Ratings for {{ $store->name }}</h3>

        <div>
            @forelse ($store->ratings()->latest()->get() as $rating)
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
        </div>


    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"> 


    <h4>Write a review for {{$store->name}}</h4>
    
    @include('errors.list')

    @if (!$user)
        <i>Please login to be able to write reviews.</i>
    @else
        {!! Form::open() !!}
                  <div class="form-group">
                    <label for="public_review">Public Review</label>
                    <textarea rows="5" class="form-control" name="public_review" id="public_review" placeholder="Public review will be published in stores page."></textarea>
                  </div>
                  <div class="form-group">
                    <label for="private_review">Private Review</label>
                    <textarea rows="5" class="form-control" name="private_review" id="private_review" placeholder="Private review will only display to store manager."></textarea>
                  </div>

                  <div class="form-group">
                    <label>Rating</label>
                    <span v-class="glyphicon:true, glyphicon-star:rating>0, glyphicon-star-empty:rating<1" v-on="click: rating=1"></span>
                    <span v-class="glyphicon:true, glyphicon-star:rating>1, glyphicon-star-empty:rating<2" v-on="click: rating=2"></span>
                    <span v-class="glyphicon:true, glyphicon-star:rating>2, glyphicon-star-empty:rating<3" v-on="click: rating=3"></span>
                    <span v-class="glyphicon:true, glyphicon-star:rating>3, glyphicon-star-empty:rating<4" v-on="click: rating=4"></span>
                    <span v-class="glyphicon:true, glyphicon-star:rating>4, glyphicon-star-empty:rating<5" v-on="click: rating=5"></span>
                  </div>

                  <input type="hidden" v-model="rating" name="rating">

                  <div style="text-align: right"><button type="submit" class="btn btn-default">Submit Review</button></div>
        {!! Form::close() !!}
    @endif


    </div>

</div>





@endsection
@section('footer')


  <script type="text/javascript">
   var vm = new Vue({
    el: '#defaultMainContainer',
    data:{
        rating : 3,
    },

    ready: function(){
                        
    

    },

    computed: {
      percentAddress: function(){

      },

    },

    methods:{
      updateStores: function(){
        var that = this;

        this.loading = true;

        $.ajax({
            type: "POST",
            url: "/search/",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                searchQuery: this.searchQuery,
                tags: JSON.stringify(this.selected),
            },
            timeout: 15000,
            dataType: 'json'
        })
        .done(function(data) { //update orders
            that.loading = false;

            if(data["error"]==0){
                that.stores = data['stores'];
            }else{
                alert(data['message']);
            }
        });

      },

    } // methods

  })
  </script>



@endsection