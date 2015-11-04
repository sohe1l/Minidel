@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Location</li>
</ol>
@endsection

@section('content')

<style>
     .controls {
        background-color: #fff;
        border-radius: 2px;
        border: 1px solid transparent;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        box-sizing: border-box;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        height: 29px;
        margin-left: 17px;
        margin-top: 10px;
        outline: none;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
      }

      .controls:focus {
        border-color: #4d90fe;
      }
</style>





<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'location'))
  </div>
  <div class="col-md-9">
    <h2>Store Location<br>
        <small>Here you can view and edit your store location.</small>
    </h2>

    @include('errors.list') 


    <div class="panel panel-default">
        <div class="panel-heading">Location Map</div>
        <div class="panel-body">
           <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
           <div id="map" style="height:400px;"></div>

           <div style="text-align: center; margin-top: 20px">
            {!! Form::open(['url' => '/manage/'.$store->slug.'/location/marker', 'class'=>'form-inline']) !!}
              <div class="form-group">
                <label for="latbox">Latitude</label>
                <input type="text" class="form-control" id="latbox" name="lat" value="{{$lat}}">
              </div>
              <div class="form-group">
                <label for="lngbox">Longitude</label>
                <input type="text" class="form-control" id="lngbox" name="lng" value="{{$lng}}">
              </div>
              <button type="submit" class="btn btn-primary">Update Marker location</button>
            {!! Form::close() !!}
            </div>

        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">Location</div>
        <div class="panel-body">
            {!! Form::model($store, array('url' => '/manage/'.$store->slug.'/location', 'class'=>'form-horizontal' ) ) !!}
            <div>
                <b>Current Location:</b>
                {{ $store->country }} / 
                {{ $store->city->name or '-' }} /
                {{ $store->area->name or '-' }} /
                {{ $store->building->name or '-' }} 
            </div>

            <br>


            <div class="form-group">
                <label class="col-md-4 control-label">Country</label>
                <div class="col-md-6">
                    {!! Form::select('country', $countries, null, $attributes=array('class'=>'form-control','id'=>'country') );  !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">City</label>
                <div class="col-md-6">
                    {!! Form::select('city_id',[],null,$attributes=array('class'=>'form-control','id'=>'city') ); !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Area</label>
                <div class="col-md-6">
                    {!! Form::select('area_id',[],null,$attributes=array('class'=>'form-control','id'=>'area') ); !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Building</label>
                <div class="col-md-6">
                    {!! Form::select('building_id',[],null,$attributes=array('class'=>'form-control','id'=>'building') ); !!}
                </div>
            </div>



            <div class="form-group">
                <div class="col-md-6 col-md-offset-4" style="text-align: right">
                    {!! Form::submit('Update Location!', $attributes=array('class'=>'btn btn-primary')); !!}
                </div>
            </div>
        {!! Form::close() !!}
        </div>
    </div>



  </div>
</div>











@endsection




@section('footer')
    <script type="text/javascript">


function initMap() {
  var myLatlng = new google.maps.LatLng({{$lat}},{{$lng}});
  
  var map = new google.maps.Map(document.getElementById('map'), {
    center: myLatlng,
    zoom: 17
  });

  var input = document.getElementById('pac-input');

  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    draggable:true
  });

  /*
  var infowindow = new google.maps.InfoWindow();

  marker.addListener('click', function() {
    infowindow.open(map, marker);
  });
*/

  autocomplete.addListener('place_changed', function() {
    //infowindow.close();
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }

    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);
    }

    document.getElementById("latbox").value = place.geometry.location.lat();
    document.getElementById("lngbox").value = place.geometry.location.lng();
    
    // Set the position of the marker using the place ID and location.
    marker.setPosition({lat:place.geometry.location.lat(), lng:place.geometry.location.lng() });
/*
    marker.setPlace({

      placeId: place.place_id,
      location: place.geometry.location
    });
//    marker.setVisible(true);


    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' +
        'Place ID: ' + place.place_id + '<br>' +
        place.formatted_address);
    infowindow.open(map, marker);
*/
  });

  google.maps.event.addListener(marker, 'dragend', function (event) {
    document.getElementById("latbox").value = this.getPosition().lat();
    document.getElementById("lngbox").value = this.getPosition().lng();
  });


}






        function update_city(){
            $.ajax({
              url: '/api/v1/country/'+ $("#country").val() +'/cities/',
              type: 'GET',
              cache: true,
              success: function(data) {
                if(data.length == 0){
                    $('#city').select2('val','');
                    $('#city').html('');
                }else{
                    $('#city').select2({data: data});
                }
                update_area();
               },
              error: function(e) { alert("Some error occurred. Please try again."); }
            });
        }

        function update_area(){
            if($("#city").val() == null ){
                helper_clear('#area');
            }else{
                $.ajax({
                  url: '/api/v1/country/'+ $("#country").val() + '/cities/' + $("#city").val() + '/areas/' ,
                  type: 'GET',
                  cache: true,
                  success: function(data) {
                    if(data.length == 0){
                        helper_clear('#area');
                    }else{
                        $('#area').select2({data: data});
                    }
                    update_building();
                   },
                  error: function(e) { alert("Some error occurred. Please try again."); }
                });
            }
        }

        function update_building(){
            if($("#city").val() == null || $("#area").val() == null ){
                helper_clear('#building');
            }else{
                $.ajax({
                  url: '/api/v1/country/'+ $("#country").val() + '/cities/' + $("#city").val() + '/areas/' + $("#area").val() + '/buildings/' ,
                  type: 'GET',
                  cache: true,
                  success: function(data) { 
                    if(data.length == 0) {
                        helper_clear('#building');
                    }else{
                        $('#building').select2({data: data});
                    }
                  },
                  error: function(e) { alert("Some error occurred. Please try again."); }
                });
            }
        }

        function helper_clear(input){
            $(input).select2('val','');
            $(input).html(' ');
        }

        $('#country').select2().on("change", update_city);
        $('#city').select2().on("change", update_area);
        $('#area').select2().on("change", update_building);
        $('#building').select2();

        update_city();

    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4ErY960Th0MmUL2qD1uZiYPRetpEqqsc&libraries=places&callback=initMap">
    </script>
@endsection