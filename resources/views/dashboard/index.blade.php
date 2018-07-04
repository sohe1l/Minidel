@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
@endsection

@section('head')

<meta name="csrf-token" content="{{ csrf_token() }}" />


<style type="text/css">
.quickOrder{
    padding:1em;
    border-bottom: 1px solid #e7e7e7;
}
.quickOrder:first-child{
    padding-top:0em;
}
</style>



@endsection



@section('content')

<div v-show="selected_address_id == 0">
<div class="titleStyled">Please select your location
<div class="pull-right" style="font-size:50%"><a href="/dashboard/address/create"><i class="glyphicon glyphicon-plus"></i> Add a New Address</a></div>
</div>
<div class="row">
  <div class="col-xs-12 col-sm-6 col-lg-4" v-for="address in addresses">
  <div class="panel panel-primary" style="margin:10px 0" v-on:click="selected_address_id = address.id">
    <div class="panel-body">
      <div style="font-size:150%; font-weight:bold">@{{ address.name }}</div>
      @{{ address.city.name }} - @{{ address.area.name }} - @{{ (address.building)?address.building.name+' - ':'' }} @{{ address.unit }}<br>
      @{{ address.info }}
    </div>
  </div>
  </div>
</div>
</div>

<div v-if="selected_address_id != 0">

<div class="row">
  <div class="col-sm-8 col-lg-9">

  <div class="titleStyled">Quick Order</div>

    <h4>@{{ selected_address.name }} 
    <small>
      @{{ selected_address.city.name }} - @{{ selected_address.area.name }} - @{{ (selected_address.building)?selected_address.building.name+' - ':'' }} @{{ selected_address.unit }} -  @{{ selected_address.info }}
    </small>
    <div class="pull-right">
      <a  class="btn btn-default btn-xs" v-on:click="selected_address_id = 0"><i class="glyphicon glyphicon-random"></i> &nbsp; Change Location</a>
    </div>

    </h4>


  <div v-show="orderComplete" class="alert alert-success" role="alert" style="margin-top:10px;">
    Order recieved successfully.
  </div>
  <div v-show="!orderComplete">



  <div class="row">
    <div class="col-sm-12" v-if="recent_orders.length == 0">
      <small>You don't have any previous orders from this location.</small>
      <div class="titleStyledGrayed">Browse stores for delivery or pickup to place your first order.</div>
    </div>
    <div class="col-sm-12" v-for="ro in recent_orders">
      
      <div class="panel panel-default" style="padding:10px;">
      <div class="row">
        <div class="col-xs-4 text-center" style="font-size: 1.5em;">
          <img class="img-responsive center-block" v-bind:src="'/img/logo/'+ro.store.logo">
          <div style="text-transform:capitalize">@{{ ro.type }}</div>
          <div>@{{ payable(ro.price,ro.fee,ro.discount) }} AED</div>
          <button type="button" class="btn btn-primary btn-block" v-on:click="placeRegular(ro.id)">Order</button>
        </div>
         <div class="col-xs-8">
        <table class="table table-condensed table-striped">
        <thead><tr><th>Quick Order</th></tr></thead>
        <tr v-for="item in ro.cart"><td>
          @{{item.quan}} <small><b>X</b></small> @{{ item.title }} 
          <div v-for="option in item.options">
            [@{{option.name}}:
              <span style="font-style: italic" v-for="subOption in option.selects">@{{ subOption.name }}</span>
            ]
          </div>
        </td></tr>
        </table>
        <div>@{{ ro.instructions }}</div>
        </div>
      </div>
      </div>







    </div>
  </div>



  </div>



  </div>
  <div class="col-sm-4 col-lg-3">
    <div class="titleStyled">Browse Stores</div>

    <div style="text-align: center" v-show="selected_address && selected_address.building && selected_address.has_mini">
      <div><a href="/dashboard/order/?type=mini&address_id=@{{selected_address_id}}" class="btn btn-danger btn-lg btn-block">
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span> Room Service
      </a></div>
      <div>Delivery from nearby stores without minimum order amount or delivery fees</div>
    </div>

    <hr style="margin-top:10px; margin-bottom:10px; border-top: 1px solid #ab7777">

    <div style="text-align: center">
      <div><a href="/dashboard/order/?type=delivery&address_id=@{{selected_address_id}}" class="btn btn-danger btn-lg btn-block">
        <span class="glyphicon glyphicon-road"></span> Delivery
      </a></div>
      <div>Delivery from any stores that delivers to your area</div>
    </div>

    <hr style="margin-top:10px; margin-bottom:10px; border-top: 1px solid #ab7777">

    <div style="text-align: center">
      <div><a href="/dashboard/order/?type=pickup&address_id=@{{selected_address_id}}" class="btn btn-danger btn-lg btn-block">
        <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Pickup
      </a></div>
    </div>
    <hr style="margin-top:10px; margin-bottom:10px; border-top: 1px solid #ab7777">

    <div v-if="selected_address && selected_address.promos.length != 0">
    <br><br>
    <div class="titleStyled">Latest Promotions</div>
      <div v-for="store in selected_address.promos">
        <a v-bind:href="'/'+store.slug+'/order/'"><img class="img-responsive" v-bind:src="'/img/cover/'+ store.cover"></a>
        <b>@{{ store.promos[0].text }}</b><br>
        <small>@{{ store.promos[0].value }}% off from @{{ store.name }}. Promotions ends on @{{ store.promos[0].end_date }}</small>
      </div>
    </div>

 </div>
</div>
</div>



<div style="color: #fe602c; font-family: lane; text-align: center; font-size: 2em; padding:50px 0;">
    <a href="/manage/create/">
    <b>Do you deliver? We offer free online ordering system for your restaurant or shop!</b>
    </a>
</div>



@stop

@section('footer')
<script type="text/javascript">
  
  var vm = new Vue({
    el: '#defaultMainContainer',
    data:{
      orderComplete : false,
      selected_address_id : 0,
      addresses: {!! json_encode($user_addresses) !!},
      all_orders: {!! json_encode($all_orders) !!}
    },
    computed:{
      selected_address: function(){
        var address_object = null;
        that = this;

        this.addresses.forEach(function(ad) {
          if(that.selected_address_id == ad.id) address_object = ad;
        });

        return address_object;
      },

      recent_orders: function(){
        var recent = [];
        that = this;
        this.all_orders.forEach(function(order) {
          if(that.selected_address_id == order.user_address_id) recent.push(order);
        });
        return recent;
      },

    },
    methods:{
      payable: function(price,fee,discount){

        var payable = Number(price) + Number(fee);

        discount = Number(discount);
        
        if(discount != 0){
          payable = payable - Math.round(discount/100*price*100)/100;
        }


        return payable;
      },
      placeRegular: function(id){
        var that = this;
        $.ajax({
          type: "POST",
          url: "/dashboard/regular",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: {
            order: id,
            timeout: 15000,
            dataType: 'json'
          }
        })
        .done(function(data) {
          if(data["error"]==1){
            if(data["error_type"] && data["error_type"] == "NO_WORKMODE"){
              swal( {title: "Notice!",   text: data["error_message"],   type: "warning",  showCancelButton: true, confirmButtonText: "Visit Store Page", closeOnConfirm: false },
                function(){
                  location.href=data["store_url"];
                });
            }else{
              sweet_error(data["error_message"]);
            }
          }else if(data["error"]==0){
            that.orderComplete = true;
            setTimeout(function(){location.href="/dashboard/orders/"} , 100); 
          }else{
            sweet_error("Network Error");
          }
        })
        .fail( function(xhr, status, error) {
          sweet_error("Some Error Occured!");
        });
      } // place order
    }
  });
</script>

@stop