@extends('layouts.default')

@section('head')
  <style>
    .itemName { font-weight: normal;}
    .cart-row {border-bottom: 1px solid #848484;     padding: 7px 0;}
    .cart-title, .cart-price {font-weight: bold; font-size: 1.1em;}
    .cart-right {float:right;}
    .btn-warning a {color:white !important;}

  </style>
@endsection


@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/superadmin/">Super Admin</a></li>
    <li><a href="/superadmin/orders/">Orders</a></li>
    <li class="active">Pending</li>
  </ol>
@endsection



@section('content')



<div class="row" id="ordersDiv">
  <div class="col-md-3 hidden-print">
    <div style="height:20px">&nbsp;</div>

    <ul class="nav nav-pills nav-stacked">
      <li v-repeat="orders" role="presentation" v-class="btn-warning:callback==1, btn-warning:status == 'pending', active: selectedId==id"><a v-on="click: selectedId=id">@{{ user.name }}</a></li>
    </ul>
  </div>
  <div class="col-md-9">

    <div v-show="!orders[selectedIndex]">
      <h2><small>No pending orders! This page is live!</small></h2>
    </div>

    <div v-show="orders[selectedIndex]">


          <h2> 
            @{{ orders[selectedIndex].store.name }}  
            <small>@{{ orders[selectedIndex].store.phone }}</small>
          </h2>

          <h4> 
            Customer: @{{ orders[selectedIndex].user.name }}  
            <small>@{{ orders[selectedIndex].user.mobile }}</small>
          </h4>

          <h3>
            <span class="label label-primary">@{{ orders[selectedIndex].type }}</span>
            <span class="label label-primary">@{{ orders[selectedIndex].payment_type.name }}</span>
            <span class="label label-default">@{{ 'Created: ' + orders[selectedIndex].created_at }}</span>
            <span class="label label-warning" v-show="orders[selectedIndex].schedule">@{{ 'Schedule: ' + orders[selectedIndex].schedule }}</span>
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
                <div v-show="item.options">
                  <span class="cart-options" v-repeat="item.options">
                    <b>@{{name}}:</b>
                    <span class="cart-options" v-repeat="selects">@{{name}} </span> 
                  </span>
                </div>
              </td>
              <td>@{{item.quan}}</td>
              <td>@{{item.quan * item.price}}</td>
            </tr>

            <tr>
              <td></td>
              <td></td>
              <td><b>@{{ orders[selectedIndex].price }}</b></td>
            </tr>

            <tr v-if="orders[selectedIndex].discount != 0">
              <td></td>
              <td>@{{ orders[selectedIndex].discount }} % discount</td>
              <td><b>@{{ Math.round(orders[selectedIndex].discount/100 * orders[selectedIndex].price*100)/100    }}</b></td>
            </tr>
            <tr v-if="orders[selectedIndex].discount != 0">
              <td></td>
              <td><b>Payable</b></td>
              <td><b>@{{ (orders[selectedIndex].price*100 + orders[selectedIndex].fee *100)/100 - Math.round(orders[selectedIndex].discount/100 * orders[selectedIndex].price*100)/100    }}</b></td>
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



          <div style="font-size: 1.8em" class="hidden-print">
            <span v-class="label:true, 
                          label-default:orders[selectedIndex].status != 'pending', 
                          label-warning:orders[selectedIndex].status == 'pending'">Pending</span>
            &nbsp;
            <span v-class="label:true, 
                          label-default:orders[selectedIndex].status != 'accepted', 
                          label-success:orders[selectedIndex].status == 'accepted'">Accepted</span>
            &nbsp;
            <span v-class="label:true, 
                          label-default:orders[selectedIndex].status != 'delivering', 
                          label-success:orders[selectedIndex].status == 'delivering'">Delivering</span>&nbsp;

            <span v-class="label:true, 
                          label-default:orders[selectedIndex].status != 'delivered', 
                          label-success:orders[selectedIndex].status == 'delivered'">Delivered</span>&nbsp;

            <span v-show="orders[selectedIndex].status == 'canceled'" class="label label-danger">Canceled</span>&nbsp;
            <span v-show="orders[selectedIndex].status == 'rejected'" class="label label-danger">Rejected</span>&nbsp;
            <span v-show="orders[selectedIndex].callback == 1" class="label label-danger">Callback Requested</span>
          </div>



          <div style="text-align: right;" class="hidden-print">

            <?php /*
            <span v-class="label:true,label-default:orders[selectedIndex].status != 'pending' ,label-danger:orders[selectedIndex].status == 'pending'" 
                style="float:left; text-transform: capitalize; font-weight: bold">
              Current Status: @{{ orders[selectedIndex].status }}
            </span>
            */ ?>
            
            
            

            <span v-show="orders[selectedIndex].callback == 1">
              <button v-on="click: setStatus('callback',0)" type="button" class="btn btn-warning">Confirm Callback</button>
            </span>


            <span v-show="orders[selectedIndex].status == 'pending'">
              <button v-on="click: setStatus('rejected',0)" type="button" class="btn btn-danger">Reject Order</button>
              <button v-on="click: setStatus('accepted',0)" type="button" class="btn btn-success">Accept Order</button>
            </span>

            <span v-show="orders[selectedIndex].status == 'accepted'">
              <button v-on="click: setStatus('delivering',0)" type="button" class="btn btn-info">On the way</button>
              <button v-on="click: setStatus('delivered',0)" type="button" class="btn btn-success">Delivered</button>
              <button v-on="click: setStatus('delivered',01)" type="button" class="btn btn-default">Delivered - Hide</button>
            </span>

            <span v-show="orders[selectedIndex].status == 'delivering'">
              <button v-on="click: setStatus('delivered',0)" type="button" class="btn btn-success">Delivered</button>
              <button v-on="click: setStatus('delivered',01)" type="button" class="btn btn-default">Delivered - Hide</button>
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

<audio id="beep" src="/img/beep.mp3" type="audio/mpeg" preload="auto" loop></audio>

  <script type="text/javascript">
  var vm = new Vue({
    el: '#defaultMainContainer',
    data:{
      orders:[],
      selectedId:0,
      status_working : ''
    },
    ready: function(){
      this.updateOrders();
      setInterval("vm.updateOrders()", 20000 );
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
          url: "/superadmin/orders/getPendingOrders",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: {
          
          },
          timeout: 15000,
          dataType: 'json'
        })
        .done(function(data) { //update orders
          that.orders = data['orders'];
          that.status_working = data['status_working'];
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
          if(item.callback == 1) has = 1;
        });
        if(has == 0) $('#beep').trigger("pause");
        if(has == 1) $('#beep').trigger("play");
      },

      setStatus: function(status,hide){   // accept / on the way / delivered /
            orderid = this.orders[this.selectedIndex].id;
            var reason = "";
            if(status == "rejected"){
              var reason = prompt("Please enter the reason. The reason will be shown to the customer.", "");
              if (reason == null || reason == '') {
                alert("You must specify a reason.");
                return false;
              }
            }

          var that = this;

          $.ajax({
            type: "POST",
            url: "/manage/" + this.orders[this.selectedIndex].store.slug + "/order/"+orderid+"/updateStatus/",
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
            if(data["error"]==1){ alert('Error'); }
          })
          .fail( function(xhr, status, error) {
            alert("Error Occured");
          });

      },

    } // methods

  })




  </script>


  <script id="optiontemplate" type="x-template">

  </script>

@endsection