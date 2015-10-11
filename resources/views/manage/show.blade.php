@extends('layouts.default')


@section('head')
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <style>
    .itemName { font-weight: normal;}
    .cart-row {border-bottom: 1px solid #848484;     padding: 7px 0;}
    .cart-title, .cart-price {font-weight: bold; font-size: 1.1em;}
    .cart-right {float:right;}
    .btn-warning a {color:white !important;}

  </style>
@endsection


@section('content')

<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li class="active">{{ $store->name }}</li>
</ol>


<div class="row" id="ordersDiv">
  <div class="col-sm-6 hidden-print">
    <span style="font-size: 2em">{{$store->name}}</span> &nbsp;&nbsp;
    <span style="font-size: 1.5em"><a href="/manage/{{$store->slug}}/general"><span class="glyphicon glyphicon-edit"></span></a></span>
  </div>
  <div class="col-sm-6 hidden-print" style="text-align: right">
    <div class="btn-group" data-toggle="buttons" style="width: 200px">
      <label v-class="btn:1, btn-default:status_working!='open', btn-success:status_working=='open' ,active:status_working=='open'"
        style="width: 33%" v-on="click: setStatusWorking('open')">
        <input type="radio" name="type" autocomplete="off" v-attr="checked: status_working=='open'"> Open
      </label>
      
      <label v-class="btn:1, btn-default:status_working!='close', btn-warning:status_working=='close' ,active:status_working=='close'"
        style="width: 33%" v-on="click: setStatusWorking('close')">
        <input type="radio" name="type" autocomplete="off" v-attr="checked: status_working=='close'"> Close
      </label>
      
      <label v-class="btn:1, btn-default:status_working!='busy', btn-danger:status_working=='busy' ,active:status_working=='busy'"
        style="width: 33%" v-on="click: setStatusWorking('busy')">
        <input type="radio" name="type" autocomplete="off" v-attr="checked: status_working=='busy'"> Busy
      </label>
    </div>
  </div>
</div>









<div class="row" id="ordersDiv">
  <div class="col-md-3 hidden-print">
    <div style="height:20px">&nbsp;</div>

    <ul class="nav nav-pills nav-stacked">
      <li v-repeat="orders" role="presentation" v-class="btn-warning:callback==1, btn-warning:status == 'pending', active: selectedId==id"><a v-on="click: selectedId=id">@{{ user.name }}</a></li>
    </ul>
  </div>
  <div class="col-md-9">

    <div v-show="!orders[selectedIndex]">
      <h2><small>Here you will recieve incoming orders!</small></h2>
    </div>

    <div v-show="orders[selectedIndex]">

          <h2> 
            @{{ orders[selectedIndex].user.name }}  
            <small>@{{ orders[selectedIndex].user.mobile }}</small>
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



          <div style="font-size: 1.8em">
            <span v-class="label:true, 
                          label-default:orders[selectedIndex].status != 'pending', 
                          label-warning:orders[selectedIndex].status == 'pending'">Pending
            </span>
            &nbsp;
            <span v-class="label:true, 
                          label-default:orders[selectedIndex].status != 'accepted', 
                          label-success:orders[selectedIndex].status == 'accepted'">Accepted
            </span>
            &nbsp;
            <span v-class="label:true, 
                          label-default:orders[selectedIndex].status != 'delivering', 
                          label-success:orders[selectedIndex].status == 'delivering'">Delivering
            </span>&nbsp;

            <span v-class="label:true, 
                          label-default:orders[selectedIndex].status != 'delivered', 
                          label-success:orders[selectedIndex].status == 'delivered'">Delivered
            </span>&nbsp;

            <span v-show="orders[selectedIndex].status == 'canceled'" class="label label-danger">Canceled</span>&nbsp;
            <span v-show="orders[selectedIndex].status == 'rejected'" class="label label-danger">Rejected</span>&nbsp;
            <span v-show="orders[selectedIndex].callback == 1" class="label label-danger">Callback Requested</span>
          </div>



          <div style="text-align: right">

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
          url: "/manage/{{$store->slug}}/getorders",
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

      setStatusWorking: function(status){
          this.status_working = status;
          $.ajax({
            type: "POST",
            url: "/manage/{{$store->slug}}/updateStatusWorking/",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
              status_working:status,
            },
            timeout: 15000,
            dataType: 'json'
          })
          .done(function(data) { //update orders
            if(data["error"]!=0){ alert(data["message"]);}
          })
          .fail( function(xhr, status, error) {
            alert("Error Occured");
          });

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
            url: "/manage/{{$store->slug}}/order/"+orderid+"/updateStatus/",
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


  <script id="optiontemplate" type="x-template">

  </script>

@endsection