@extends('layouts.default')

@section('head')
  <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')

<style>
.highlight {
    padding: 9px 14px;
    margin-bottom: 14px;
    background-color: #f7f7f9;
    border: 1px solid #e1e1e8;
    border-radius: 4px;
}

.affix{top:10px;}



.modal-header {
    background: #e4e4e4;
    border-radius: 3px 3px 0 0;
    border-bottom: 1px solid #e4e4e4;
    position: relative;
    padding: 3px 0
}

.modal-title {
    line-height: 30px;
    margin: 3px 20px 0;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase
}

.modal-close {
    padding: 8px 8px 0 0 !important
}

.modal-info {
}


#modalOptions .btn-group{ width: 100%}
#modalOptions .btn-group button{ width: 50%}

.optionItem{padding:5px; border-top:1px solid #D4D4D4;} 
.optionError{color:red;}

.itemName { font-weight: normal;}



.cart-row {border-bottom: 1px solid #D0D0D0;     padding: 7px 0;}
.cart-title, .cart-price {font-weight: bold; font-size: 0.9em;}
.cart-options{ font-size: 0.8em;}
.cart-right {float:right;}

.cart-row .glyphicon{font-size: 90%; color:#545454; }















@media(max-width:767px){
  .affix { position: static; }
  #headingSmall {font-size:0.5em;}
  #cartContainer{ width: 100% }
  .container {padding:0;}

}
@media(min-width:768px){
  #storePhone {float:right}
  #cartContainer{ width: 240px }
  .menuSections{ width:100px; float:left; }
  .menuSections table{ width: 100px; font-size:1.1em;}
  .menuContainer{ width: calc(100% - 100px - 15px); margin-left: 15px; float:left;}
}
@media(min-width:992px){
  #cartContainer{ width: 320px }
  .menuSections{ width:130px; float:left; }
  .menuSections table{ width: 130px; font-size:1.2em;}
  .menuContainer{ width: calc(100% - 130px - 15px); margin-left: 15px; float:left;}
}
@media(min-width:1200px){
  #cartContainer{ width: 390px }
  .menuSections{ width:200px; float:left; }
  .menuSections table{ width: 200px; font-size:1.3em;}
  .menuContainer{ width: calc(100% - 200px - 15px); margin-left: 15px; float:left;}
}









html, body { overflow-x: hidden;}

footer { padding: 30px 0;} 

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

<h2 style="margin-top: 0;">{{ $store->name }} 
  <small id="headingSmall">
    <span id="storePhone" style="padding-top: 8px;"><span class="glyphicon glyphicon-phone-alt"></span> {{ $store->phone }}</span>
    {{ $store->building->name }} - {{ $area->name }}
  </small>
</h2>

<div class="row row-offcanvas row-offcanvas-right">


  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
    <div style="position: relative; margin-bottom: 20px;">
      <img src="/img/cover/{{ $store->cover}}" class="img-responsive" style="border-radius: 3px;">
      <img src="/img/logo/{{ $store->logo}}" class="img-thumbnail" style="position: absolute; bottom:10px; left:10px; max-width:20%;">
    </div>




    <div class="hidden-xs menuSections">
      <table class="table" data-spy="affix" data-offset-top="310"> 
      @foreach ($store->sections as $section)
        <tr><td>{{ $section->title }}</td></tr>
      @endforeach
      </table>

      <hr>
      <small></small>


    </div>
    <div class="menuContainer">

      <div v-show="!isLogin" class="alert alert-danger" role="alert">Please login to be able to place orders.</div>

      <div v-show="isLogin && !hasAddresses" class="alert alert-danger" role="alert">
        You don't have any address in your system therefore you cannot place orders for delivery.
        (click to Add your address)
      </div>

      <div v-show="isLogin && hasAddresses && !userAddresses" class="alert alert-warning" role="alert">
        None of your delivery addresses matches the delivery coverage of this resturant. You can still place orders for pickup.<br>
        <?php /*
        Your addresses: <span v-repeat="userAddresses">@{{$value}} </span>  <br>
        Resturant delivery locations: <span>{{ $store->coverageBuildings->keyBy('name')->keys()->implode(', ') }}</span>
        */ ?>
      </div>


      <div v-show="!{!! $store->isOpenNow('Building Delivery') !!}" class="alert alert-danger" role="alert">
        Store is not open for delivery moment.
      </div>

        <div v-show="!{!! $store->isOpenNow('Normal Openning') !!}" class="alert alert-danger" role="alert">
        Store is not open for pick up orders at the moment.
      </div>




              @forelse ($store->sections as $section)

                    
                              <div class="panel panel-default">
                                  <div class="panel-heading">{{ $section->title }}</div>
                                  <table class="table">
                                  @forelse ($section->items->sortBy('order') as $item)
                                    <tr>
                                      <td style="width:100px"><img src="/img/menu/{{ $item->photo or 'placeholder.svg' }}" class="img-thumbnail"></td>
                                      <td>
                                        <b>{{ $item->title }}</b> - {{ $item->price }}

                                        
                                        <span v-on="click: addItem({{ $item->id }})" title="Add" style="float:right">
                                          <span v-if="quans[{{$item->id}}]" class="badge" style="background-color: #f0ad4e">
                                            {{ " {"."{". "quans[".$item->id  ."] }"."} " }}
                                          </span>
                                          &nbsp;
                                          <span class="glyphicon glyphicon-plus" style="color:#f0ad4e"></span>
                                        </span>

                                        <br>
                                        {{ $item->info }}
                                      </td>
                                    </tr>
                                  @empty
                                    <tr><td>No menu items in this section!</td></tr>
                                  @endforelse

                                  </table>

                                   
                              </div>
              @empty
                <h4 style="text-align: center">No menu available!</h4>
              @endforelse
    </div>
  </div>


  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 sidebar-offcanvas" id="sidebar"> 

    <div class="highlight" v-show="isLogin" data-spy="affix" data-offset-top="310" id="cartContainer">
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

            <div class="cart-options"><span class="cart-options" v-repeat="item.options"><b>@{{name}}:</b> @{{selects}} </span></div>
        </div>

        <div v-show="cart.length < 1">
          <small style="font-style: italic;">Select items from the menu.</small>
        </div>

        <div v-show="cart.length > 0">

          <div style="text-align: right">Total: Dhs @{{ totalPrice }} dhs</div>    


          <div class="form-horizontal">
            <div class="form-group">
              <label class="col-md-4 col-lg-3 control-label" style="margin: 0;">Option</label>
              <div class="col-md-8 col-lg-9">
                <label class="radio-inline">
                  <input type="radio" name="dorp" v-model="dorp" value="delivery" v-attr="disabled: !userAddresses"> Delivery
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
              <label for="inputPassword3" class="col-md-4 col-lg-3 control-label">
                <span class="glyphicon glyphicon-remove" aria-label="remove" v-on="click:showTimes = false" ></span>
                Schedule Time
              </label>
              <div class="col-md-8 col-lg-9" style="padding-top: 5px;">
                <select v-model="deliveryDay" options="deliveryDays"></select>
                <select v-model="deliveryTime" options="deliveryTimes"></select>
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

  <div v-show="!showStoreTimings" v-on="click: showStoreTimings = true" style="font-style: italic">Show Store Timings</div>
  <div v-show="showStoreTimings">
    <b>Delivery Timings</b>
    @foreach ($store->timings()->sortByDay()->where('workmode_id',1)->get() as $timings)
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
      hasAddresses: {{ ($user && $user->hasAddresses())?'true':'false' }},
      userAddresses: {!! ($user)? json_encode($user->listAddressesByStore($store->id)):'null' !!},
      items: {!! $store->itemsWithOptions !!},
      deliveryDays: {!! json_encode($daysDelivery) !!},
      pickupDays: {!! json_encode($daysPickup) !!},
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
      if(this.userAddresses) this.dorp = "delivery";

    },


    computed: {
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
          this.deliveryDays.forEach(function(item){
            if(item.value == that.deliveryDay) times = item.deliveryTime;
          });
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
      modifyOptions: 
        function (value) {
          alert("this shoudl not run");
          /*
          value.forEach(function(mainOptions){

            var optionType = "checkbox";
            if(mainOptions.min == 1 && mainOptions.max ==1){
              optionType = "radio";
            }
            mainOptions.options.forEach(function(item) {
              item.type = optionType;
              item.isSelected = false;
            });

          });
          return value;
        */
      },
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
              selectedVals.push(item.name);
              priceOptions = priceOptions + Number(item.price);

            }
          });
          if(selectedVals != []) selectedOptions.push({name:suboption.name, selects:selectedVals.join(", ")});
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

      setOption: function (itemID, optionsID, optionID){
        alert("this never runs");
        if(this.modalItem.id != itemID) alert("something is wrong");
        this.modalItem.options.forEach(function(item){
          item.options.forEach( function(option){
            option.isSelected = 999;
          });
        });
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

        var that = this;

        $.ajax({
          type: "POST",
          url: "/store/{{$store->city->slug}}/{{$store->area->slug}}/{{$store->slug}}",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: {
            showTimes: this.showTimes,
            cart: this.cart,
            dorp:this.dorp,
            instructions:this.instructions,
            day:this.deliveryDay,
            time:this.deliveryTime,
            address: this.selectedAddress,
            totalPrice: this.totalPrice },
          timeout: 15000,
          dataType: 'json'
        })
        .done(function(data) {
          if(data["error"]==1){
            alert(data["message"]);
          }else{
            that.orderComplete = true;
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
            if(this.numSelected() >= this.min && this.numSelected() <= this.max)
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

            if(this.numSelected() > this.max){
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
      <b>@{{optionName}}</b> [@{{min}}:@{{max}}]
    </div>
    <div class="optionItem" v-repeat="option: optionsData">
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
  </script>

@endsection