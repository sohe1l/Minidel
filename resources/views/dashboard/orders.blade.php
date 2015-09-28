@extends('layouts.default')


@section('head')
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <style>
    .itemName { font-weight: normal;}
    .cart-row {border-bottom: 1px solid #848484;     padding: 7px 0;}
    .cart-title, .cart-price {font-weight: bold; font-size: 1.1em;}
    .cart-right {float:right;}
  </style>
@endsection


@section('content')

<div class="row" id="ordersDiv">
  <div class="col-md-3 hidden-print">
    <div style="height:20px">&nbsp;</div>
    <ul class="nav nav-pills nav-stacked">
      <li v-repeat="orders" role="presentation" v-class="btn-danger:status == 'pending', active: selectedId==id"><a v-on="click: selectedId=id">@{{ store.name }}</a></li>





    </ul>
  </div>
  <div class="col-md-9">

    <div v-show="!orders[selectedIndex]">

      <h2>{{$user->name}}<br>
          <small>You will see your orders here! This page is live with your order status!</small>
      </h2>

    </div>

    <div v-show="orders[selectedIndex]">

          <h2> 
            @{{ orders[selectedIndex].store.name }}  
            <small>@{{ orders[selectedIndex].store.phone }}</small>
          </h2>

          <h3>
            <span class="label label-primary">@{{ orders[selectedIndex].type }}</span>
            <span class="label label-warning">@{{ orders[selectedIndex].schedule }}</span>
          </h3>

          <br>

          <table class="table">
            <tr>
              <th>Item</th>
              <th>Quantity</th>
              <th>Total Price</th>
            </tr>

            <tr v-repeat="item: cart">
              <td>@{{item.title}}
                <div v-show="item.options"><span class="cart-options" v-repeat="item.options"><b>@{{name}}:</b> @{{selects}} </span></div>
              </td>
              <td>@{{item.quan}}</td>
              <td>@{{item.quan * item.price}}</td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td><b>@{{ orders[selectedIndex].price }}</b></td>

            </tr>

          </table>

          <blockquote v-show="orders[selectedIndex].user_address">
            @{{ orders[selectedIndex].user_address.name }}
            @{{ orders[selectedIndex].user_address.phone }}
            @{{ orders[selectedIndex].user_address.area.name }}
            @{{ orders[selectedIndex].user_address.building.name }}
          </blockquote>                

          <blockquote v-show="orders[selectedIndex].instructions" style="border-left:5px solid #F34F4F">
            @{{ orders[selectedIndex].instructions }}
          </blockquote>



          <blockquote v-show="orders[selectedIndex].reason != null" style="border-left:5px solid #F3884F">
                <b>Explanation:</b> @{{ orders[selectedIndex].reason }}
          </blockquote>



          <div style="text-align: right">
            <span v-class="label:true,label-default:orders[selectedIndex].status != 'pending' ,label-danger:orders[selectedIndex].status == 'pending'" 
                style="float:left; text-transform: capitalize; font-weight: bold">
              Current Status: @{{ orders[selectedIndex].status }}
            </span>

            <span style="float:left;">&nbsp;&nbsp;</span>
            
            <span v-show="orders[selectedIndex].callback == 1" class="label label-danger" style="float:left; font-weight: bold">
              Callback Requested
            </span>


            <span v-show="orders[selectedIndex].status == 'pending'">
              <button v-on="click: setStatus('canceled',0)" type="button" class="btn btn-danger">Cancel Order</button>
            </span>

            <span v-show="orders[selectedIndex].status == 'accepted'">
              <button v-on="click: setStatus('callback',0)" type="button" class="btn btn-info">Request Call Back</button>
            </span>

            <span v-show="orders[selectedIndex].status == 'delivering'">
              <button v-on="click: setStatus('callback',0)" type="button" class="btn btn-info">Request Call Back</button>
            </span>

            <span v-show="orders[selectedIndex].status == 'delivered'">
              <button v-on="click: setStatus('',1)" type="button" class="btn btn-default">Hide</button>
            </span>

            <span v-show="orders[selectedIndex].status == 'canceled'">
              <button v-on="click: setStatus('',1)" type="button" class="btn btn-default">Hide</button>
            </span>

            <span v-show="orders[selectedIndex].status == 'rejected'">
              <button v-on="click: setStatus('',1)" type="button" class="btn btn-default">Hide</button>
            </span>
          </div>

    </div>





  </div>
</div>












@endsection




@section('footer')

  <script type="text/javascript">
  var vm = new Vue({
    el: '#ordersDiv',
    data:{
      orders:[],
      selectedId:0,
    },
    ready: function(){
      this.updateOrders();
      setInterval("vm.updateOrders()", 10000 );
    },
    computed: {
      selectedIndex: function(){
        var index = -1;
        var that = this;
        this.orders.forEach(function(item, key){
          if(item.id == that.selectedId) index = key;
        });
        return index;
      },
      cart: function(){
        return JSON.parse(this.orders[this.selectedIndex].cart);
      },
    },

    methods:{
      updateOrders: function(){
        var that = this;

        $.ajax({
          type: "POST",
          url: "/dashboard/orders/getorders",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: {
          
          },
          timeout: 15000,
          dataType: 'json'
        })
        .done(function(data) { //update orders
          that.orders = data['orders'];
          that.checkPending();
        })
        .fail( function(xhr, status, error) {
          alert("Error Occured");
        })
        .always(function() {
          
        });

      },

      checkPending: function(){
        var has = 0;
        this.orders.forEach(function(item, key){
          if(item.status == 'pending') has = 1;
        });
        if(has == 0) $('#beep').trigger("pause");
        if(has == 1) $('#beep').trigger("play");
      },

      setStatus: function(status,hide){   // accept / on the way / delivered /
            orderid = this.orders[this.selectedIndex].id;
            var reason = "";
            if(status == "canceled"){
              var reason = prompt("Please enter the reason. The reason will be shown to the customer.", "");
              if (reason == null || reason == '') {
                alert("You must specify a reason.");
                return false;
              }
            }

          var that = this;

          $.ajax({
            type: "POST",
            url: "/dashboard/orders/"+orderid+"/updateStatus/",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
              status:status,
              hide:hide,
              reason:reason
            },
            timeout: 15000,
            dataType: 'json'
          })
          .done(function(data) { //update orders
            if(data["error"]==0){ that.orders = data['orders']; that.checkPending();}
          })
          .fail( function(xhr, status, error) {
            alert("Error Occured");
          });

      },

    } // methods

  })




  </script>

@endsection