@extends('layouts.default')

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
  color: #3E3E3E;
  font-size: 90%;
}


#modalOptions .btn-group{ width: 100%}
#modalOptions .btn-group button{ width: 50%}

.optionItem{padding:5px; border-top:1px solid #D4D4D4;} 
.optionError{color:red;}

.itemName { font-weight: normal;}



.cart-row {border-bottom: 1px solid #848484;     padding: 7px 0;}
.cart-title, .cart-price {font-weight: bold; font-size: 1.1em;}
.cart-right {float:right;}
















@media(max-width:767px){
  .container {padding:0;}

}
@media(min-width:768px){
  .menuSections{ width:100px; float:left; }
  .menuSections table{ width: 100px; font-size:1.1em;}
  .menuContainer{ width: calc(100% - 100px - 15px); margin-left: 15px; float:left;}
}
@media(min-width:992px){
  .menuSections{ width:130px; float:left; }
  .menuSections table{ width: 130px; font-size:1.2em;}
  .menuContainer{ width: calc(100% - 130px - 15px); margin-left: 15px; float:left;}
}
@media(min-width:1200px){
  .menuSections{ width:200px; float:left; }
  .menuSections table{ width: 200px; font-size:1.3em;}
  .menuContainer{ width: calc(100% - 215px); margin-left: 15px; float:left;}
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





<h2 style="margin-top: 0;">{{ $store->name }} 
  <small>
    <div style="float:right;"><span class="glyphicon glyphicon-phone-alt"></span> {{ $store->phone }}</div>
    {{ $store->building->name }} - {{ $area->name }}
  </small>
</h2>

<div class="row row-offcanvas row-offcanvas-right" id="storeDiv">


  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
    <div style="position: relative; margin-bottom: 20px;">
      <img src="/img/cover/{{ $store->cover}}" class="img-responsive" style="border-radius: 3px;">
      <img src="/img/logo/{{ $store->logo}}" class="img-thumbnail" style="position: absolute; bottom:10px; left:10px; max-width:20%;">
    </div>

    <div class="visible-xs-block">
      <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
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



              @forelse ($store->sections as $section)

                    
                              <div class="panel panel-default">
                                  <div class="panel-heading">
                                    {{ $section->title }}
                                    <a href="javascript:deleteSection({{ $section->id }})">
                                      <span class="glyphicon glyphicon-remove" style="float:right"></span>
                                    </a>
                                  </div>
                                  <table class="table">
                                  @forelse ($section->items->sortBy('order') as $item)
                                    <tr>
                                      <td style="width:100px"><img src="/img/menu/{{ $item->photo or 'placeholder.svg' }}" class="img-thumbnail"></td>
                                      <td>
                                        <b>{{ $item->title }}</b> - {{ $item->price }}<br>
                                        {{ $item->info }}
                                      </td>
                                      <td style="width:100px">


                                        <a v-on="click: addItem({{ $item->id }})" title="Edit">
                                          <span class="glyphicon glyphicon-pencil"></span>
                                        </a>

                          
                                        
                                        
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

    <div class="highlight">
      <h3 style="margin:0">Your Order</h3>
      <div v-show="!isLogin">Login</div>

      <div v-show="isLogin && !hasAddresses">Add an address</div>

      <div v-show="isLogin && hasAddresses && !userAddresses">
        None of your delivery addresses matches the delivery coverage of this resturant.<br>

        Your addresses: <span v-repeat="userAddresses"> @{{$value}} </span>  <br>

        Resturant delivery locations: <span>{{ $store->coverageBuildings->keyBy('name')->keys()->implode(', ') }}</span>



        


      </div>


      <div v-show="userAddresses && isLogin">
        <div class="cart-row" v-repeat="item: cart">
            @{{item.quan}}
            <span class="cart-title">@{{item.title}}</span>
            <span class="cart-right">
              <span class="cart-price">@{{item.price}}</span>
              <span class="glyphicon glyphicon-remove cart-remove" v-on="click:removeItem(item)" ></span>
            </span>
            <div><span class="cart-options" v-repeat="item.options"><b>@{{name}}:</b> @{{selects}} </span></div>

        </div>

        <h4 style="text-align: right">Total: @{{ totalPrice }} dhs</h4>    


        
        <div style="text-align: center">
          <div class="btn-group" data-toggle="buttons" >
              <label class="btn btn-default" v-repeat="userAddresses">
                  <input type="radio" name="address" value="@{{$index}}" /> @{{$value}}
              </label> 
          </div>
        </div>


        <div style="text-align: center">
          <div class="btn-group" data-toggle="buttons" >
              <label class="btn btn-default">
                  <input type="radio" id="q156" name="quality[25]" value="1" /> Delivery
              </label> 
              <label class="btn btn-default">
                  <input type="radio" id="q157" name="quality[25]" value="2" /> Pickup
              </label>
          </div>
        </div>

        <div style="text-align: center; margin-top:10px;">
          <button type="button" 
          class="btn btn-primary"
          v-on="click: placeOrder">Place order @{{ (cart.length<1)?'disabled':'no' }}  </button>
        </div>

        <button type="button" class="btn btn-primary btn-xs visible-xs-block" data-toggle="offcanvas">Toggle nav</button>
      </div>

    </div>


    <pre>
    @{{ $data | json}}
    </pre>


    <pre>
    @{{ vm.$$.item1.html }}
    </pre>

    <?php /*
      <small>
        <b>Delivery Timings</b>
        @foreach ($store->timings()->sortByDay()->where('workmode_id',1)->get() as $timings)
            <div {!! (strtolower(date('D'))==$timings->day)?' style="font-weight:bold"':'' !!}>
              {{ \Config::get('vars.days')[$timings->day] }}:
              {{ $timings->start }} to {{ $timings->end }}
            </div>
        @endforeach
      </small>
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



</div>



<button type="button" class="btn btn-primary btn-xs visible-xs-block" data-toggle="offcanvas">Toggle nav</button>

@endsection

@section('footer')


<script type="text/javascript">
  
$(document).ready(function () {
  $('[data-toggle="offcanvas"]').click(function () {
    $('.row-offcanvas').toggleClass('active');
    window.scrollTo(0,0);
  });
});

new Vue({
  el: '#storeDiv',

  data:{
    cart: [],
    modalItem: [],
    isLogin: {{ ($user) ?'true':'false' }},
    hasAddresses: {{ $user->hasAddresses()?'true':'false' }},
    userAddresses: {!! ($user)? json_encode($user->listAddressesByStore($store->id)):'null' !!},
    items: {!! $store->itemsWithOptions !!},
    optionsval: '',


  },

  computed: {
    totalPrice: function(){
      var total = 0;
      this.cart.forEach(function(item) {
        total = total + item.price*item.quan;
      });
      return total;
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
            price:newItem.price,
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

      this.modalItem.options.forEach(function(suboption){
        var selectedVals = [];
        suboption.options.forEach(function(item){
          if(item.isSelected) selectedVals.push(item.name);
        });
        if(selectedVals != []) selectedOptions.push({name:suboption.name, selects:selectedVals.join(", ")});
      });


      this.cart.push({
        id:this.modalItem.id,
        title:this.modalItem.title,
        name:this.modalItem.name,
        price:this.modalItem.price,
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

    placeOrder: function(){
      if(this.cart.length < 1){
        alert("No item in the cart");
        return false;
      }
    }

  },


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
        checkone: function(){ //uncheck all or except id
          this.updateOption($("#el_2_4"),4,true);
        },
        uncheckAll: function(id){ //uncheck all or except id
          var that = this;
          id = typeof id !== 'undefined' ? id : 0;
          this.optionsData.forEach(function(singleOption, index){
            if(singleOption.id != id && singleOption.isSelected == true){ 
              that.updateOption($("#el_" + singleOption.menu_option_id + "_" + singleOption.id),singleOption.id,false);
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
        updateOption: function(el,id,isSelected){
          var newItem = [];
          this.optionsData.forEach(function(singleOption, index){
            if(singleOption.id == id) singleOption.isSelected = isSelected;
            newItem.push(singleOption); 
          });
          this.optionsData = newItem;
          if(isSelected == true) $(el).attr('checked', 'checked');
          if(isSelected == false) $(el).attr('checked', false);
          
        },
        selectOption: function(id,type,e){   //update isSelected on the option
          var isSelected = $(e.target).is(":checked");
          this.updateOption($(e.target),id,isSelected);
          if(this.numSelected() > this.max){
            if(this.max==1 && this.min==1){
              this.uncheckAll(id)
            }else{ // undo
              this.updateOption(e.target,id,false);
            }
          }
          if(this.numSelected() < this.min){
            // bug
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
      v-on="change: selectOption(option.id,option.type,$event)"
      type="checkbox" 
      name="@{{option.menu_option_id}}"
      id="el_@{{option.menu_option_id}}_@{{option.id}}"
      v-attr="checked: option.isSelected"
      > @{{option.isSelected}}
    <label class="itemName" for="el_@{{option.menu_option_id}}_@{{option.id}}">@{{option.name}}</label>
  </div>
</script>

@endsection