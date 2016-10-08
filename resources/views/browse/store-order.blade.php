<?php $extends = (isset($inline))?'layouts.inline':'layouts.default'; ?>@extends($extends)

@section('bodyProp')data-spy="scroll" data-target="#navbar-menu" style="position: relative"@endsection

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

#storeDiv a {
    color: #333333;
    font-size: 0.9em;
}



#storeDiv li{
    border-bottom: 1px solid #E4E4E4;
    border-radius: 0 !important;
}

#storeDiv li.active a{
    background-color: inherit !important;
    font-weight: bold;
    color:#f0ad4e !important;
    font-size: 1.2em;

}

.itemPhoto{width:150px; margin-right:10px; padding:0;}



@media(max-width:767px){

    body { padding-bottom: 40px; }

}




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

.panel-danger > .panel-heading { background-color: #F9F6F6 }

</style>




<div id="storeDiv">


<!-- <button style="width:100%" type="button" class="visible-xs-block btn btn-info" data-toggle="offcanvas">@{{toggleText}}</button> -->

<h3 style="margin-top: 0;">
  {{ $store->name }}
  <span class="label label-success" style="font-size:45%">{{ $store->is_open == 'true'?'Open Now':''  }}</span>&nbsp;
  <span class="label label-danger" style="font-size:45%">{{ $store->is_open == 'false'?'Closed Now':''  }}</span>&nbsp;

  <small id="headingSmall">
    <span v-show="{{ $store->status_working!='open'?'true':'false' }}" class="label label-danger">{{ $store->status_working  }}</span>
    <span id="storePhone" style="padding-top: 8px;"><span class="glyphicon glyphicon-phone-alt"></span> {{ $store->phone }}</span>
  </small>
</h3>

<div class="hidden-xs" style="position: relative; margin-bottom: 20px;">

<img src="/img/cover/{{ $store->cover}}" class="img-responsive" style="border-radius: 3px;">
<?php /*
  <div style=" background: url(/img/cover/{{ $store->cover}}) no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; height:390px;">
      </div>  
  */ ?>
      <img src="/img/logo/{{ $store->logo}}" class="img-thumbnail" style="position: absolute; bottom:10px; left:10px; max-width:20%;">
</div>

<div class="row row-offcanvas row-offcanvas-right">

  <div class="hidden-xs hidden-sm col-md-2 col-lg-2" id="menuSectionsParent">
            <div class="menuSections">
            <nav data-spy="affix" data-offset-top="420" id="navbar-menu" class="whiteBG">
            @foreach ($store->sections->where('menu_section_id',null)->where('available',1) as $section)
              <ul class="nav nav-pills nav-stacked">
                <li><a class="collapseCntNav" data-toggle="collapse" data-parent="#menuContainer" href="#section{{$section->id}}" aria-expanded="false" aria-controls="#section{{$section->id}}" v-on:click="menuClicked()">{{ $section->title }}</a></li>
              </ul>

              @foreach ($section->subsections->where('available',1) as $subsection)
                <ul class="nav nav-pills nav-stacked">
                  <li><a class="collapseCntNav" data-toggle="collapse" data-parent="#menuContainer" href="#section{{$subsection->id}}" aria-expanded="false" aria-controls="#section{{$subsection->id}}" style="color: #fe602c" v-on:click="menuClicked()">{{ $subsection->title }}</a></li>
                </ul>
              @endforeach
            @endforeach





            </nav>
            <?php /*
                  <table class="table" data-spy="affix" data-offset-top="310"> 
                  @foreach ($store->sections->where('menu_section_id',null)->where('available',1) as $section)
                    <tr><td><a href="#section{{$section->id}}">{{ $section->title }}</a></td></tr>
                    @foreach ($section->subsections->where('available',1) as $subsection)
                      <tr><td style="font-size:70%;"><a href="#section{{$subsection->id}}">{{ $subsection->title }}</a></td></tr>
                    @endforeach
                  @endforeach
                  </table>
            */ ?>
          </div>
  </div>

  <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
    <div class="visible-xs" style="position: relative; margin-bottom: 20px;">
      <img src="/img/cover-mobile/{{ $store->cover}}" class="img-responsive" style="border-radius: 3px;">
      <img src="/img/logo/{{ $store->logo}}" class="img-thumbnail" style="position: absolute; bottom:10px; left:10px; max-width:20%; max-height:100px;">
    </div>

    
    <div class="menuContainer" id="menuContainer">

      <div v-show="!isLogin" class="alert alert-danger" role="alert">
        Please login to be able to place orders. <a href="/auth/login/?redirect={{ \Request::path() }}"><b>Click here to login now.</b></a></div>

      <div v-show="isLogin && !hasAddresses" class="alert alert-warning" role="alert">
        None of your delivery addresses matches the delivery coverage of this resturant. You can still place orders for pickup.<br>
        <?php /*
        Your addresses: <span v-repeat="userAddresses">@{{$value}} </span>  <br>
        Resturant delivery locations: <span>{{ $store->coverageBuildings->keyBy('name')->keys()->implode(', ') }}</span>
        */ ?>
      </div>
      
      @forelse ($store->sections->where('menu_section_id',null)->where('available',1) as $section)              
        <div class="panel panel-danger" style="margin-bottom:0; border:0;">
          <div class="panel-heading">
          
        <a class="collapsed collapseCnt" role="button" data-toggle="collapse" data-parent="#menuContainer" href="#section{{$section->id}}" aria-expanded="false" aria-controls="#section{{$section->id}}" style="font-size:1.2em">
          {{ $section->title }} <span class="glyphicon glyphicon-menu-right"></span>
        </a>


            

          </div>

          <div id="section{{$section->id}}" class="panel-collapse collapse">
          <table class="table">
            @foreach ($section->items->sortBy('order') as $item)
              @include('browse._item')
            @endforeach
            
            @foreach ($section->subsections->where('available',1) as $subsection)
            <tr><td colspan="2" id="section{{$subsection->id}}"><b style="color: #fe602c; font-size: 1.15em">{{ $subsection->title }}</b></td></tr>
              @foreach ($subsection->items->sortBy('order') as $item)
                @include('browse._item')
              @endforeach
            @endforeach

          </table>
          </div>
        </div>
      @empty
        <h4 style="text-align: center">No menu available!</h4>
      @endforelse
    </div>
  </div>


  <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 sidebar-offcanvas" id="sidebar" style="font-family: Futura, 'Trebuchet MS', Arial, sans-serif;"> 

    <div class="alert alert-success" role="alert" v-if="activePromo">
      Promotion: @{{activePromo.value}}% discount on total bill<br>
      @{{ activePromo.text }}
    </div>

    <div class="alert alert-success" role="alert" v-if="!activePromo && currentFees.discount>0">
      Discount: @{{currentFees.discount}}% discount on total bill
    </div>



    @if(! $is_online)
    <div class="alert alert-danger" role="alert">
      The store is not currenly online, however you may still place an order for a future time.
    </div>
    @endif
    
    <div class="cart-highlight" v-show="isLogin" data-spy="affix" data-offset-top="800" id="cartContainer">
      <h4 style="margin:0; color: #fe602c;">Your Order</h4>

      <div v-show="isLogin && !orderComplete">
        <div class="cart-row" v-for="item in cart">
            <span class="cart-title">@{{item.title}}

            <span class="cart-right">
                <span class="cart-price">@{{item.quan * item.price}}</span>
              </span>
            </span>
            
            <div class="cart-options">
              <span class="glyphicon glyphicon-remove" aria-label="remove" v-on:click="removeItem(item)" ></span>
              &nbsp; [dhs @{{item.price}}]
              &nbsp;&nbsp; 
              <span class="glyphicon glyphicon-minus" aria-label="minus" v-on:click="cartMines(item)"></span>
              &nbsp; @{{item.quan}} &nbsp;
              <span class="glyphicon glyphicon-plus" aria-label="plus" v-on:click="cartPlus(item)"></span>

              
            </div>

            <div class="cart-options">
              <span class="cart-options" v-for="io in item.options">
                <b>@{{io.name}}:</b>
                <span v-for="ios in io.selects">
                  @{{ios.name}}
                </span>
              </span>
            </div>
        </div>

        <div v-show="cart.length < 1">
          <small style="font-style: italic;">Select items from the menu.</small>
        </div>






        <div> <?php // v-show="cart.length > 0" ?>
          <div style="color: #fe602c; font-size:1.2em">
            <div style="text-align: right" v-show="deliveryFee!=0 && dorp=='delivery'">Delivery Fee: Dhs @{{ deliveryFee }} dhs</div>     
            
            <div style="text-align: right; font-weight: bold">Total: Dhs @{{ totalPrice + deliveryFee  }} dhs</div>   

            <template v-if="discountAmount">
              <div style="text-align: right; font-weight: bold">
              Discount: @{{ discountAmount }} dhs
                <br>
              Payable: @{{ totalPrice + deliveryFee - discountAmount }} dhs
              </div>
            </template>
          </div>




          <div class="form-horizontal">

            <div class="form-group">
              <label class="col-md-4 col-lg-3 control-label" style="margin: 0;">Payment</label>
              <div class="col-md-8 col-lg-9">
                <label class="radio-inline" v-for="pm in paymentMethods">
                  <input type="radio" 
                         name="payment"
                         v-model="payment"
                         v-bind:checked="$index==0"
                         value="@{{pm.pivot.payment_type_id}}"> @{{pm.name}}
                </label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 col-lg-3 control-label" style="margin: 0;">Option</label>
              <div class="col-md-8 col-lg-9">
                <label class="radio-inline">
                  <input type="radio" name="dorp" v-model="dorp" value="delivery" v-bind:disabled="!hasAddresses"> Delivery
                </label>
                <label class="radio-inline">
                  <input type="radio" name="dorp" v-model="dorp" value="pickup"> Pickup
                </label>
              </div>
            </div>

            <div class="form-group" v-show="dorp =='delivery'">
              <label class="col-md-4 col-lg-3 control-label" style="margin: 0;">Location</label>
              <div class="col-md-8 col-lg-9">
                <label class="radio-inline" v-for="ua in userAddresses">
                  <input type="radio" 
                         name="selectedAddress"
                         v-model="selectedAddress"
                         v-bind:checked="$index==0"
                         value="@{{ua.value}}"> @{{ua.text}}
                </label>
              </div>
            </div>


            <div v-show="!schedule.status" v-on:click="schedule.status = true" style="font-style: italic">
                Schedule
            </div>

            <div class="form-group" v-show="schedule.status">
              <label for="inputPassword3" class="col-md-4 col-lg-3 control-label">Schedule</label>
              <div class="col-md-8 col-lg-9" style="padding-top: 5px;">


                <select v-model="schedule.day">
                  @for ($c = \Carbon\Carbon::today(); $c < \Carbon\Carbon::now()->addDays(5) ; $c->addDay())
                  <option value="{{ strtolower($c->format('D')) }}">{{ $c->format('l') }}</option>
                  @endfor
                </select>

                <select v-model="schedule.hour">
                <option v-for="option in availableTimesHours" v-if="option.available" v-bind:value="option.value">
                  @{{ option.text }}
                </option>
                </select>
                :
                <select v-model="schedule.min">
                <option v-for="option in availableTimesMins" v-if="option.available" v-bind:value="option.value">
                  @{{ option.text }}
                </option>
                </select>


                <span class="glyphicon glyphicon-remove" aria-label="remove" v-on:click="clearSchedule" ></span>
              </div>
            </div>

            <div v-show="!showInstruction" v-on:click="showInstruction = true" style="font-style: italic">
                Add Instructions
            </div>

            <div class="form-group" v-show="showInstruction">
              <div class="col-md-12 col-lg-12">
                <textarea class="form-control" placeholder="No tomatoes!" v-model="instructions" name="orderInstructions"></textarea>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-offset-4 col-lg-offset-3 col-md-8 col-lg-9" style="text-align: right">
                <button type="submit" class="btn btn-danger" v-on:click="placeOrder">Place Order</button>
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

  <div style="line-height: 30px;" v-if="dorp == 'delivery'">
    <template v-if="availableMode.bldg && addressObj.bldg != null">
      <span class="label label-success">Room Service</span> <br>
    </template>
    <template v-if="currentFees.min != 0">
      <span class="label label-info">@{{ currentFees.min }} dhs Minimum Delivery</span> <br>
    </template>
    <template v-if="currentFees.fee != 0">
      <span class="label label-info">@{{ currentFees.fee }} dhs Delivery Fee</span> <br>
    </template>
    <template v-if="currentFees.feebelowmin != 0">
      <span class="label label-info">@{{ currentFees.feebelowmin }} dhs Delivery Fee (below min)</span> <br>
    </template>
  </div>

  <div v-if="!availableMode.area && !availableMode.bldg" class="alert alert-danger" role="alert">
    <template v-if="schedule.status">
      The store does not deliver to your selected address in the selected time. Please select a different day/time.
    </template>
    <template v-else>
      The store is currently not delivering to your selected address. Select a future schedule time for your order.
    </template>

  </div>

  <div style="line-height: 20px; font-size: 80%">Store Last Online: {{ $last_online }}</div>

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
        <div v-for="mio in modalItem.options">
          <optionitem 
            :options-data.sync="mio.options"
            :option-id="mio.id"
            :is-valid.sync="mio.isValid"
            :min="mio.min"
            :max="mio.max"
            :option-name="mio.name">
          </optionitem>
        </div>

<?php /*

            <!-- handler="@{{optionsHandler}}" -->

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
          <button type="button" class="btn btn-success" v-on:click="addItemModal">Add Item</button>
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
        <a href="/auth/login/?redirect={{ \Request::path() }}" class="btn btn-primary">Ok!</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal Options -->

<!-- <button style="width:100%" type="button" class="visible-xs-block btn btn-info" data-toggle="offcanvas">@{{toggleText}}</button> -->

<nav class="navbar navbar-default navbar-fixed-bottom visible-xs" style="min-height: 30px; background-color:#FE602C">
  <div class="container">
    <button style="width:100%; border-width:0" type="button" class="visible-xs-block btn btn-primary" data-toggle="offcanvas">@{{toggleText}}</button>
  </div>
</nav>

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


  //  $(window).bind('resize load', function() {
    if ($(this).width() < 992) {
        $('.panel-collapse').removeClass('in');
        $('.panel-collapse').addClass('out');
    } else {
        $('.collapseCntNav').removeAttr('data-toggle');
        $('.collapseCnt').removeAttr('data-toggle');
        $('.collapseCnt').removeAttr('href');
        $('.panel-collapse').removeClass('out');
        $('.panel-collapse').addClass('in');
    }
  //});


  });

  var vm = new Vue({
    el: '#storeDiv',

    data:{
      workmode: {bldg:1, area:2, pickup:5},
      cart: [],
      modalItem: [],
      isLogin: {{ ($user) ?'true':'false' }},
      userAddresses: {!! json_encode($user_addresses) !!},
      items: {!! $store->items !!},
      payment: '',
      paymentMethods: {!! json_encode($store->payments) !!},
      storeTimings: {!! json_encode($storeTimings) !!},
      schedule: {'day':'', 'hour':'', 'min':'', 'status':false},
      optionsval: '',
      selectedAddress: '',
      dorp : 'pickup',
      instructions: '',
      showInstruction: false,
      showStoreTimings: false,
      orderComplete: false,
      toggleText : 'View Your Order', // did not work with computed
      activePromo: {!! json_encode($active_promos) !!},
    },
    ready: function(){
      if(this.hasAddresses) this.dorp = "delivery";
      window.addEventListener('resize', this.handleResize)
      this.handleResize();
    },



    computed: {

      availableTimesHours: function(){
        var hours = [
        {'value':'00', 'text':'12 AM', 'available':0},
        {'value':'01', 'text':'1 AM', 'available':0},
        {'value':'02', 'text':'2 AM', 'available':0},
        {'value':'03', 'text':'3 AM', 'available':0},
        {'value':'04', 'text':'4 AM', 'available':0},
        {'value':'05', 'text':'5 AM', 'available':0},
        {'value':'06', 'text':'6 AM', 'available':0},
        {'value':'07', 'text':'7 AM', 'available':0},
        {'value':'08', 'text':'8 AM', 'available':0},
        {'value':'09', 'text':'9 AM', 'available':0},
        {'value':'10', 'text':'10 AM', 'available':0},
        {'value':'11', 'text':'11 AM', 'available':0},
        {'value':'12', 'text':'12 PM', 'available':0},
        {'value':'13', 'text':'1 PM', 'available':0},
        {'value':'14', 'text':'2 PM', 'available':0},
        {'value':'15', 'text':'3 PM', 'available':0},
        {'value':'16', 'text':'4 PM', 'available':0},
        {'value':'17', 'text':'5 PM', 'available':0},
        {'value':'18', 'text':'6 PM', 'available':0},
        {'value':'19', 'text':'7 PM', 'available':0},
        {'value':'20', 'text':'8 PM', 'available':0},
        {'value':'21', 'text':'9 PM', 'available':0},
        {'value':'22', 'text':'10 PM', 'available':0},
        {'value':'23', 'text':'11 PM', 'available':0}
        ];

        that = this;

        this.storeTimings.forEach(function(item){
          if(item.day == that.schedule.day){
            var start_hour = parseInt( item.start.split(":")[0] );
            var end_hour = parseInt( item.end.split(":")[0] );
            hours.forEach(function(h){
              if(h.value >= start_hour &&  h.value <= end_hour) h.available = 1;
            });

          }
          //if(that.selectedAddress == item.value ) obj = item
        })

        return hours;

      },

      availableTimesMins: function(){

        var mins = [
        {'value':'00', 'text':'00', 'available':0},
        {'value':'05', 'text':'05', 'available':0},
        {'value':'10', 'text':'10', 'available':0},
        {'value':'15', 'text':'15', 'available':0},
        {'value':'20', 'text':'20', 'available':0},
        {'value':'25', 'text':'25', 'available':0},
        {'value':'30', 'text':'30', 'available':0},
        {'value':'35', 'text':'35', 'available':0},
        {'value':'40', 'text':'40', 'available':0},
        {'value':'45', 'text':'45', 'available':0},
        {'value':'50', 'text':'50', 'available':0},
        {'value':'55', 'text':'55', 'available':0},
        ];

        that = this;

        this.storeTimings.forEach(function(item){
          if(item.day == that.schedule.day){
            var start_hour = parseInt( item.start.split(":")[0] );
            var end_hour = parseInt( item.end.split(":")[0] );
            var start_min = parseInt( item.start.split(":")[1] );
            var end_min = parseInt( item.end.split(":")[1] );
            
            mins.forEach(function(m){
              if(that.schedule.hour > start_hour && that.schedule.hour  < end_hour){
                m.available = 1;
                //alert("case 1");
              }else if(that.schedule.hour == start_hour){
                if(m.value >= start_min) m.available = 1;
                //alert("case 2");
              }else if(that.schedule.hour == end_hour){
                if(m.value <= end_min) m.available = 1;
                //alert("case 3");
              }
            });

          }
          //if(that.selectedAddress == item.value ) obj = item
        })
        return mins;
      },

      availableMode: function(){
        if(this.schedule.day && this.schedule.hour && this.schedule.min){
          return this.calAvailableModes(this.schedule.day, this.schedule.hour, this.schedule.min);
        }else{
          return this.calAvailableModes({!! strtolower(date("'D',G,i")) !!});
        }
      },

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

      currentFees: function(){
        var res = {'min':0, 'fee':0, 'feebelowmin':0, 'discount':0};
        if(this.dorp == 'pickup'){
          return res;
        }
        if(this.availableMode.bldg && this.addressObj.bldg){
            res.min = this.addressObj.bldg.min;
            res.fee = this.addressObj.bldg.fee;
            res.feebelowmin = this.addressObj.bldg.feebelowmin;
            res.discount = this.addressObj.bldg.discount;
            return res; // bldg priority
        }
        if(this.availableMode.area && this.addressObj.area){
            res.min = this.addressObj.area.min;
            res.fee = this.addressObj.area.fee;
            res.feebelowmin = this.addressObj.area.feebelowmin;
            res.discount = this.addressObj.area.discount
        }
        return res;
      },
      deliveryFee: function(){
        if(this.totalPrice == 0 ) return 0;
        if(this.totalPrice < this.currentFees.min && this.currentFees.feebelowmin != 0 ) return this.currentFees.feebelowmin;
        else return this.currentFees.fee;
        //if(this.dorp == 'pickup') return 0; 
        // if(this.totalPrice < this.currentFees.min){
        //   if(this.currentFees.feebelowmin != 0) return this.currentFees.feebelowmin
        //   else return ""
        // }else{
        //   return this.currentFees.fee
        // }
      },
      
      discountAmount: function(){
        if(this.activePromo) return Math.round(this.activePromo.value/100*this.totalPrice*100)/100;
        if(this.dorp == 'pickup') return 0;
        if(this.currentFees.discount == 0) return 0;
        else return Math.round(this.currentFees.discount/100*this.totalPrice*100)/100
        return 0;
      },
      totalPrice: function(){
        var total = 0;
        this.cart.forEach(function(item) {
          total = total + item.price*item.quan;
        });
        return total;
      },
      
      // deliveryTimes: function(){
      //   var that = this;
      //   var times;
      //   if(this.dorp == 'delivery'){
      //     if(this.addressObj.type == "mini"){
      //       this.deliveryMini.forEach(function(item){
      //         if(item.value == that.deliveryDay) times = item.deliveryTime;
      //       });
      //     }
      //     if(this.addressObj.type == "delivery"){
      //       this.deliveryDays.forEach(function(item){
      //         if(item.value == that.deliveryDay) times = item.deliveryTime;
      //       });
      //     }


      //   }else{
      //     this.pickupDays.forEach(function(item){
      //       if(item.value == that.deliveryDay) times = item.deliveryTime;
      //     });
      //   }

      //   return times;
      // },
      quans: function(){ // total quantity of an item 
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
      clearSchedule: function(){
        this.schedule.status = false;
        this.schedule.day = '';
        this.schedule.hour = '';
        this.schedule.min = '';
      },

      calAvailableModes: function(c_day,c_hour,c_min){
        var res = {'bldg':false, 'area':false, 'pickup':false};
        that = this;

        this.storeTimings.forEach(function(item){
          var matching = false;

          if(item.day == c_day){

            var start_hour = parseInt( item.start.split(":")[0] );
            var end_hour = parseInt( item.end.split(":")[0] );
            var start_min = parseInt( item.start.split(":")[1] );
            var end_min = parseInt( item.end.split(":")[1] );

            if(c_hour > start_hour && c_hour  < end_hour){
              matching = true;
            }else if(c_hour == start_hour){
              if(c_min >= start_min) matching = true;
            }else if(c_hour == end_hour){
              if(c_min <= end_min) matching = true;
            }

            if(matching){
              if(that.workmode.bldg == item.workmode_id) res.bldg = true;
              if(that.workmode.area == item.workmode_id) res.area = true;
              if(that.workmode.pickup == item.workmode_id) res.pickup = true;
            }
          }
        })
        return res;
      },
      menuClicked: function(){
          setTimeout(function () {
            var y = $(window).scrollTop();  //your current y position on the page
            $(window).scrollTop(y-45);
          }, 100)
            
      },
      handleResize: function(){
        $('#navbar-menu').width( $('#menuSectionsParent').width()  );
        $('#cartContainer').width( $('#sidebar').width()-30  ); 
      },
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
      // optionsHandler: function(id){
      //   alert(id)
      // },
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
          sweet_error("Not valid options");
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

      cartMines: function(item){
        if(item.quan != 1 )
          item.quan = item.quan - 1;
      },

      cartPlus: function(item){
        item.quan = item.quan + 1;
      },

      placeOrder: function(){
        if(this.cart.length < 1){
          sweet_error("No item in the cart");
          return false;
        }

        if(this.totalPrice < this.currentFees.min && this.currentFees.feebelowmin == 0){
          sweet_error("Cannot order below minimum delivery.");
          return false;
        }

        if(this.payment == ''){
          sweet_error("Please select a payment option.");
          return false;
        }

        var that = this;


        $.ajax({
          type: "POST",
          url: "/{{$store->slug}}/order",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: {
            cart: JSON.stringify(this.cart),
            schedule: JSON.stringify(this.schedule),
            dorp:this.dorp,
            instructions:this.instructions,
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
            sweet_error(data["error_message"]);
          }else{
            that.orderComplete = true;
            setTimeout(function(){location.href="/dashboard/orders/"} , 100);   
          }
        })
        .fail( function(xhr, status, error) {
          sweet_error("Network Error Occured!");
        });
      } // place order
    }, //methods
    components: {
      'optionitem': {
        props: ['optionsData','optionId', 'min', 'max', 'handler', 'isValid','optionName'],
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
    <div :class="{'optionError': !isValid}" style="font-weight: bold; margin-top:1em;">
      <b>@{{optionName}}</b> [select @{{min}} to @{{max}}]
    </div>
    <div v-for="option in optionsData">
      <div v-if="option.available == 1">
        <div style="float:right">@{{option.price}}</div>
        <input
          v-on:change="selectOption(option,$event)"
          type="checkbox" 
          name="@{{option.menu_option_id}}"
          id="el_@{{option.menu_option_id}}_@{{option.id}}"
          v-bind:checked="option.isSelected"
          >
        <label class="itemName" for="el_@{{option.menu_option_id}}_@{{option.id}}">@{{option.name}}</label>
      </div>
    </div>
  </script>

@endsection