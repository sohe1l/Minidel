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
    <li><a href="/{{$store->slug}}">{{$store->name}}</a></li>
    <li class="active">Order</li>
  </ol>
@endsection


@section('content')

<style>
html, body { overflow-x: hidden;}

footer { padding: 30px 0;} 

#storeDiv a {color: #333333 !important;}

/*
 * Off Canvas
 * --------------------------------------------------
 */
@media screen and (max-width: 767px) {
  .row-offcanvas {
    position: relative;
    -webkit-transition: all .25s ease-out;
         -o-transition: all .25s ease-out;
            transition: all .25s ease-out;
  }

  .row-offcanvas-right {
    right: 0;
  }

  .row-offcanvas-right
  .sidebar-offcanvas {
    right: -100%; /* 6 columns */
  }

  .row-offcanvas-right.active {
    right: 107%; /* 6 columns */
  }

  .sidebar-offcanvas {
    position: absolute;
    top: 0;
    width: 100%; /* 6 columns */
  }
}
</style>




<div id="storeDiv">


<button style="width:100%" type="button" class="visible-xs-block btn btn-info" data-toggle="offcanvas">@{{toggleText}}</button>

<h3 style="margin-top: 0;">{{ $store->name }} 

  <span v-show="{{ $store->status_working!='open'?'true':'false' }}" class="label label-danger">{{ $store->status_working  }}</span>&nbsp;

  <small id="headingSmall">
    <span id="storePhone" style="padding-top: 8px;"><span class="glyphicon glyphicon-phone-alt"></span> {{ $store->phone }}</span>
    <div>{{ ($store->building)?$store->building->name:'' }} - {{ $store->area->name }}</div>
  </small>
</h3>

<div class="row row-offcanvas row-offcanvas-right">


  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
    <div style="position: relative; margin-bottom: 20px;">
      <img src="/img/cover/{{ $store->cover}}" class="img-responsive" style="border-radius: 3px;">
      <img src="/img/logo/{{ $store->logo}}" class="img-thumbnail" style="position: absolute; bottom:10px; left:10px; max-width:20%;">
    </div>




    <div class="hidden-xs menuSections">
      <table class="table" data-spy="affix" data-offset-top="310"> 
      @foreach ($store->sections->where('menu_section_id',null)->where('available',1) as $section)
        <tr><td><a href="#section{{$section->id}}">{{ $section->title }}</a></td></tr>
        @foreach ($section->subsections->where('available',1) as $subsection)
          <tr><td style="font-size:70%;"><a href="#section{{$subsection->id}}">{{ $subsection->title }}</a></td></tr>
        @endforeach
      @endforeach
      </table>

      <hr>
      <small></small>


    </div>
    <div class="menuContainer">

      <div v-show="!isLogin" class="alert alert-danger" role="alert">Please login to be able to place orders.</div>

      <div v-show="isLogin && !hasAddresses" class="alert alert-warning" role="alert">
        None of your delivery addresses matches the delivery coverage of this resturant. You can still place orders for pickup.<br>
        <?php /*
        Your addresses: <span v-repeat="userAddresses">@{{$value}} </span>  <br>
        Resturant delivery locations: <span>{{ $store->coverageBuildings->keyBy('name')->keys()->implode(', ') }}</span>
        */ ?>
      </div>
      
      @forelse ($store->sections->where('menu_section_id',null)->where('available',1) as $section)              
        <div class="panel panel-default">
          <div class="panel-heading"><a name="section{{$section->id}}">{{ $section->title }}</a></div>
          
          <table class="table">
            @foreach ($section->items->sortBy('order') as $item)
              @include('browse._item')
            @endforeach
            
            @foreach ($section->subsections->where('available',1) as $subsection)
            <tr><td colspan="2"><a name="section{{$subsection->id}}"><b>{{ $subsection->title }}</b></a></td></tr>
              @foreach ($subsection->items->sortBy('order') as $item)
                @include('browse._item')
              @endforeach
            @endforeach

          </table>
        </div>
      @empty
        <h4 style="text-align: center">No menu available!</h4>
      @endforelse
    </div>
  </div>


  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 sidebar-offcanvas" id="sidebar"> 

    <div class="cart-highlight" v-show="isLogin" data-spy="affix" data-offset-top="310" id="cartContainer">
      <h4 style="margin:0">Your Order</h4>

      <div v-show="isLogin && !orderComplete">
        <div class="cart-row" v-repeat="item: cart">
            <span class="cart-title">@{{item.title}}</span>
            
            <div class="cart-options">
              <span class="glyphicon glyphicon-remove" aria-label="remove" v-on="click:removeItem(item)" ></span>
              &nbsp; [dhs @{{item.price}}]
              &nbsp;&nbsp; 
              <span class="glyphicon glyphicon-minus" aria-label="minus" v-on="click: cartMines(item)"></span>
              &nbsp; @{{item.quan}} &nbsp;
              <span class="glyphicon glyphicon-plus" aria-label="plus" v-on="click: cartPlus(item)"></span>

              <span class="cart-right">
                <span class="cart-price">@{{item.quan * item.price}}</span>
              </span>
            </div>

            <div class="cart-options">
              <span class="cart-options" v-repeat="item.options">
                <b>@{{name}}:</b>
                <span v-repeat="selects">
                  @{{name}}
                </span>
              </span>
            </div>
        </div>

        <div v-show="cart.length < 1">
          <small style="font-style: italic;">Select items from the menu.</small>
        </div>






        <div v-show="cart.length > 0">
          <div style="text-align: right" v-show="deliveryFee!=0 && dorp=='delivery'">Delivery Fee: Dhs @{{ deliveryFee }} dhs</div>     
          
          <div style="text-align: right; font-weight: bold">Total: Dhs @{{ totalPrice + deliveryFee  }} dhs</div>   

          <div style="text-align: right; font-weight: bold" v-show="dorp=='delivery' && addressObj.discount!=0">
          Discount: @{{ discountAmount }} dhs
            <Br>
          Payable: @{{ totalPrice + deliveryFee - discountAmount }} dhs
          </div>  

          <div class="form-horizontal">
            <div class="form-group">
              <label class="col-md-4 col-lg-3 control-label" style="margin: 0;">Option</label>
              <div class="col-md-8 col-lg-9">
                <label class="radio-inline">
                  <input type="radio" name="dorp" v-model="dorp" value="delivery" v-attr="disabled: !hasAddresses"> Delivery
                </label>
                <label class="radio-inline">
                  <input type="radio" name="dorp" v-model="dorp" value="pickup"> Pickup
                </label>
              </div>
            </div>

            <div class="form-group" v-show="dorp =='delivery'">
              <label class="col-md-4 col-lg-3 control-label" style="margin: 0;">Location</label>
              <div class="col-md-8 col-lg-9">
                <label class="radio-inline" v-repeat="userAddresses">
                  <input type="radio" 
                         name="selectedAddress"
                         v-model="selectedAddress"
                         v-attr="checked: $index==0"
                         value="@{{value}}"> @{{text}}
                </label>
              </div>
            </div>


            <div v-show="!showTimes" v-on="click: showTimes = true" style="font-style: italic">
                Schedule Delivery
            </div>

            <div class="form-group" v-show="showTimes">
              <label for="inputPassword3" class="col-md-4 col-lg-3 control-label">Schedule</label>
              <div class="col-md-8 col-lg-9" style="padding-top: 5px;">
                <select v-model="deliveryDay" options="deliveryDays"></select>
                <select v-model="deliveryTime" options="deliveryTimes"></select>
                <span class="glyphicon glyphicon-remove" aria-label="remove" v-on="click:showTimes = false" ></span>
              </div>
            </div>

            <div v-show="!showInstruction" v-on="click: showInstruction = true" style="font-style: italic">
                Add Instructions
            </div>

            <div class="form-group" v-show="showInstruction">
              <div class="col-md-12 col-lg-12">
                <textarea class="form-control" placeholder="No tomatoes!" v-model="instructions" name="orderInstructions"></textarea>
              </div>
            </div>


            <div class="form-group">
              <label class="col-md-4 col-lg-3 control-label" style="margin: 0;">Payment</label>
              <div class="col-md-8 col-lg-9">
                <label class="radio-inline" v-repeat="paymentMethods">
                  <input type="radio" 
                         name="payment"
                         v-model="payment"
                         v-attr="checked: $index==0"
                         value="@{{pivot.payment_type_id}}"> @{{name}}
                </label>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-offset-4 col-lg-offset-3 col-md-8 col-lg-9" style="text-align: right">
                <button type="submit" class="btn btn-primary" v-on="click: placeOrder">Place Order</button>
              </div>
            </div>
          </div>
      </div>

    </div>

    <div v-show="orderComplete" class="alert alert-success" role="alert" style="margin-top:10px;">
      Order recieved successfully.
    </div>

<?php /*

    @{{quans | json}}

    <pre>
    @{{ $data | json}}
    </pre>

    <pre>
    @{{ vm.$$.item1.html }}
    </pre>

*/ ?>
    



  </div>


  <div style="line-height: 30px;" v-show="dorp == 'delivery'">
    <span v-if="addressObj.min && addressObj.min!=0" class="label label-info">Minimum Delivery @{{ addressObj.min }}</span>&nbsp;
    <span v-if="addressObj.fee && addressObj.fee!=0" class="label label-info">Delivery Fee @{{ addressObj.fee }}</span>&nbsp;
    <span v-if="addressObj.feebelowmin && addressObj.feebelowmin!=0" class="label label-info">Delivery Fee (below minimum) @{{ addressObj.feebelowmin }}</span>
    <span v-if="addressObj.discount && addressObj.discount!=0" class="label label-info">Discount @{{ addressObj.discount }} %</span>&nbsp;
  </div>

  <div style="line-height: 30px;">
      <span class="label label-success">{{ $store->is_open == 'true'?'Open Now':''  }}</span>&nbsp;
      <span class="label label-danger">{{ $store->is_open == 'false'?'Closed Now':''  }}</span>&nbsp;

      <span class="label label-success" v-show="addressObj.type == 'mini'">{{ $store->is_deliver_building=='true'?'Deliveres Now':''  }}</span>&nbsp;
      <span class="label label-success" v-show="addressObj.type == 'delivery'">{{ $store->is_deliver_area=='true'?'Deliveres Now':''  }}</span>
  </div>

<?php /*
  <div v-show="!showStoreTimings" v-on="click: showStoreTimings = true" style="font-style: italic; font-size: 90%">Show Store Timings</div>
  <div v-show="showStoreTimings">
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
*/ ?>
</div>

<!-- Modal Options -->
<div class="modal fade" id="modalOptions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@{{ modalItem.title }}</h4>
      </div>
      <div class="modal-body">
        <div class="modal-info">@{{ modalItem.info }}</div>
        <p>Price: @{{ modalItem.price }}</p>
        <div v-repeat="modalItem.options">
          <optionitem 
            class="optionitem" 
            handler="@{{optionsHandler}}"
            options-data="@{{@ options }}"
            option-id="@{{ id }}"
            is-valid="@{{@ isValid }}"
            min="@{{ min }}"
            max="@{{ max }}"
            option-name="@{{name}}">
          </optionitem>
        </div>

<?php /*
          <table class="table table-hover table-condensed" v-repeat="modalItem.options | modifyOptions">
            <thead>
              <tr>
                <th>
                  @{{name}} Min:@{{min}} Max:@{{max}}
                </th>
              </tr>
            </thead>
            <tbody>
              <optionitem> </optionitem>
              <optionitem v-repeat="options"> </optionitem>
             <tr >
                <td>
                <div class="itemTitle" style="float:right">@{{price}}</div>
                <input type="@{{type}}" name="@{{menu_option_id}}" v-model="checkedOption" value="@{{id}}">
                <span class="itemTitle">@{{name}}</span>
                </td>
              </tr>
            
            </tbody>
          </table>
 */ ?>

        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success" v-on="click: addItemModal">Add Item</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Options -->


<!-- Modal Options -->
<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Please login!</h4>
      </div>
      <div class="modal-body">
        Please login to be able to place an order !
      </div>
      <div class="modal-footer" style="padding: 10px">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Ok!</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Options -->

<button style="width:100%" type="button" class="visible-xs-block btn btn-info" data-toggle="offcanvas">@{{toggleText}}</button>


</div>

</div>

@endsection
@section('footer')


  <script type="text/javascript">
    
  $(document).ready(function () {
    $('[data-toggle="offcanvas"]').click(function () {
      $('.row-offcanvas').toggleClass('active');
      if($('.row-offcanvas').hasClass('active')) vm.toggleText = "View Menu";
      else vm.toggleText = "View Your Order";
      window.scrollTo(0,0);
    });
  });

  var vm = new Vue({
    el: '#storeDiv',

    data:{
      cart: [],
      modalItem: [],
      isLogin: {{ ($user) ?'true':'false' }},
      userAddresses: {!! json_encode($user_addresses) !!},
      items: {!! $store->items !!},
      deliveryMini: {!! json_encode($daysMini) !!},
      deliveryDays: {!! json_encode($daysDelivery) !!},
      pickupDays: {!! json_encode($daysPickup) !!},
      payment: '',
      paymentMethods: {!! json_encode($store->payments) !!},
      deliveryDay: '',
      deliveryTimes: '',
      deliveryTime: '',
      optionsval: '',
      selectedAddress: '',
      dorp : 'pickup',
      instructions: '',
      showInstruction: false,
      showTimes: false,
      showStoreTimings: false,
      orderComplete: false,
      toggleText : 'View Your Order', // did not work with computed


    },
    ready: function(){
      if(this.hasAddresses) this.dorp = "delivery";
    },


    computed: {
      hasAddresses: function(){
        if(this.userAddresses.length == 0) return false;
        return true;
      },
      addressObj: function(){
        var obj = "";
        var that = this;
        this.userAddresses.forEach(function(item){
          if(that.selectedAddress == item.value ) obj = item
        })
        return obj;
      },
      deliveryFee: function(){
        if(this.dorp == 'pickup') return 0; 
        if(this.totalPrice < this.addressObj.min){
          if(this.addressObj.feebelowmin != 0) return this.addressObj.feebelowmin
          else return ""
        }else{
          return this.addressObj.fee
        }
      },
      
      discountAmount: function(){
        if(this.dorp == 'pickup') return 0;
        if(this.addressObj.discount == 0) return 0;
        return Math.round(this.addressObj.discount/100*this.totalPrice*100)/100
      },
      totalPrice: function(){
        var total = 0;
        this.cart.forEach(function(item) {
          total = total + item.price*item.quan;
        });
        return total;
      },
      deliveryTimes: function(){
        var that = this;
        var times;
        if(this.dorp == 'delivery'){
          if(this.addressObj.type == "mini"){
            this.deliveryMini.forEach(function(item){
              if(item.value == that.deliveryDay) times = item.deliveryTime;
            });
          }
          if(this.addressObj.type == "delivery"){
            this.deliveryDays.forEach(function(item){
              if(item.value == that.deliveryDay) times = item.deliveryTime;
            });
          }


        }else{
          this.pickupDays.forEach(function(item){
            if(item.value == that.deliveryDay) times = item.deliveryTime;
          });
        }

        return times;
      },
      quans: function(){
        var quan = {};
        this.cart.forEach(function (item){
          if(item.id in quan) quan[item.id] = quan[item.id] + item.quan;
          else quan[item.id] = item.quan;
          
        });
        return quan;
      }
    },


    filters:{
      extractOptions: 
        function (value, keyToExtract) {
          return value.map(function (item) {
            return {text: item.name, value:item.id }
          })
        }

    },



    methods:{
      addIsSelected: function(item){
        var newitem = (JSON.parse(JSON.stringify(item)));

        newitem.options.forEach(function(mainOptions){
          mainOptions.isValid = false;
            mainOptions.options.forEach(function(singleOption) {
              singleOption.isSelected = false;
            });
          });
        return newitem;
      },
      optionsHandler: function(id){
        alert(id)
      },
      addItem: function(id){
        if(!this.isLogin){
          $('#modalLogin').modal('show'); 
          return 0;
        }
        //get the item
        newItem = this.getItemByID(id);

        //check if item has options
        if(!jQuery.isEmptyObject(newItem.options)){
          this.modalItem = this.addIsSelected(newItem);
          $('#modalOptions').modal('show');      
        }else{

          var exists = 0;

          this.cart.forEach(function(item) {
            if(item.id == id){
              item.quan = item.quan + 1;
              exists = 1;
              return false;
            }
          });

          if(!exists){
            this.cart.push({
              id:newItem.id,
              title:newItem.title,
              name:newItem.name,
              price: Number(newItem.price),
              quan:1,
              options:[]
            });
          }
        }

      },

      addItemModal: function(e){
        var valid = true;
        this.modalItem.options.forEach(function(suboption){
          if(suboption.isValid == false){
            valid = false;
          }
        });
        if(!valid){
          alert("Not valid options");
          return false;
        }

        var selectedOptions = [];
        var priceOptions = 0;

        this.modalItem.options.forEach(function(suboption){
          var selectedVals = [];
          suboption.options.forEach(function(item){
            if(item.isSelected){
              selectedVals.push({id:item.id, name:item.name});
              priceOptions = priceOptions + Number(item.price);

            }
          });
          if(selectedVals.length != 0) selectedOptions.push({id:suboption.id, name:suboption.name, selects:selectedVals});
        });


        this.cart.push({
          id:this.modalItem.id,
          title:this.modalItem.title,
          name:this.modalItem.name,
          price: Number(this.modalItem.price) + priceOptions,
          quan:1,
          options: selectedOptions,
        });
        this.modalItem = [];
        $('#modalOptions').modal('hide')
      },



      removeItem: function(item){
        this.cart.$remove(item)
      },

      getItemByID: function(id){
        var myItem = null;
        $(this.items).each(function() {
          if(id == this.id){
            myItem = this;
            return false;
          }
        });
        return(myItem);
      },


      selectOption: function(e){
            alert(id);
            alert(type);

      },

      cartMines: function(item){
        if(item.quan != 1 )
          item.quan = item.quan - 1;
      },

      cartPlus: function(item){
        item.quan = item.quan + 1;
      },

      placeOrder: function(){
        if(this.cart.length < 1){
          alert("No item in the cart");
          return false;
        }

        if(this.totalPrice < this.addressObj.min && this.addressObj.feebelowmin == 0){
          alert("Cannot order below minimum delivery.");
          return false;
        }

        if(this.payment == ''){
          alert("Please select a payment option.");
          return false;
        }

        var that = this;


        $.ajax({
          type: "POST",
          url: "/{{$store->slug}}/order",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: {
            showTimes: this.showTimes,
            cart: JSON.stringify(this.cart),
            dorp:this.dorp,
            instructions:this.instructions,
            day:this.deliveryDay,
            time:this.deliveryTime,
            address: this.selectedAddress,
            payment: this.payment,
            totalPrice: this.totalPrice,
            fee: this.deliveryFee,
            discountAmount: this.discountAmount
            },
          timeout: 15000,
          dataType: 'json'
        })
        .done(function(data) {
          if(data["error"]==1){
            alert(data["message"]);
          }else{
            that.orderComplete = true;
            setTimeout(function(){location.href="/dashboard/orders/"} , 100);   
          }
        })
        .fail( function(xhr, status, error) {
          alert("Some Error Occured!");
        });
      } // place order
    }, //methods
    components: {
      'optionitem': {
        props: ['options-data','option-id', 'min', 'max', 'handler', 'is-valid','option-name'],
        template: '#optiontemplate',
        ready: function () {
          this.validiate();
        },
        methods:{
          validiate: function(){
            if(this.numSelected() >= this.min && (this.numSelected() <= this.max || this.max == 0 ))
              this.isValid = true;
            else
              this.isValid = false;

          },
          uncheckAll: function(id){ //uncheck all or except id
            var that = this;
            id = typeof id !== 'undefined' ? id : 0;
            this.optionsData.forEach(function(singleOption, index){
              if(singleOption.id != id && singleOption.isSelected == true){ 
                singleOption.isSelected = false;
              }
            }); 
          },
          numSelected: function(){
            var num = 0;
            this.optionsData.forEach(function(singleOption, index){
              if(singleOption.isSelected) num++;
            });
            return num;
          },
          selectOption: function(option,e){   //update isSelected on the option
            var isSelected = $(e.target).is(":checked");
            option.isSelected  = isSelected;
            that = this;

            if(this.numSelected() > this.max && this.max != 0){
              if(this.max==1 && this.min==1){
                this.uncheckAll(option.id)
              }else{ // undo
                Vue.nextTick(function () {
                  option.isSelected  = false;
                  that.validiate();
                })
              }
            }
            if(this.numSelected() < this.min){
                Vue.nextTick(function () {
                  option.isSelected  = true;
                  that.validiate();
                })
            }
            this.validiate();
          }
        }
      }
    }




  })




  </script>


  <script id="optiontemplate" type="x-template">
    <div v-class="optionError: !isValid" style="font-weight: bold; margin-top:1em;">
      <b>@{{optionName}}</b> [select @{{min}} to @{{max}}]
    </div>
    <div v-repeat="option: optionsData">
      <div class="optionItem" v-if="option.available == 1">
        <div class="itemTitle" style="float:right">@{{option.price}}</div>
        <input
          v-on="change: selectOption(option,$event)"
          type="checkbox" 
          name="@{{option.menu_option_id}}"
          id="el_@{{option.menu_option_id}}_@{{option.id}}"
          v-attr="checked: option.isSelected"
          >
        <label class="itemName" for="el_@{{option.menu_option_id}}_@{{option.id}}">@{{option.name}}</label>
      </div>
    </div>
  </script>

@endsection