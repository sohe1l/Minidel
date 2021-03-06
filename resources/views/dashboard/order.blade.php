@extends('layouts.default')


@section('head')
<style type="text/css">
.btn-default.active{
  color: #e12f33;
  background-color: #FFFFFF;
/*
    color: #FFF;
    background-color: #f0ad4e;
    border-color: #eea236;
*/
}
.btn-default.active:hover{
  color: #e12f33;
 
 /*   color: #fff;
    background-color: #ec971f;
    border-color: #985f0d;
  */
}
</style>
@endsection



@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li><a href="/dashboard/">Dashboard</a></li>
    <li class="active">Order</li>
  </ol>
@endsection


@section('content')



<div class="titleStyled">Find a store</div>


<div class="row">
  <div class="col-sm-4 col-md-3">






<div class="btn-group" data-toggle="buttons" style="width: 100%">
  <label class="btn btn-default" :class="{'active': selected.type=='mini'}" style="width: 44%" v-on:click="selected.type='mini'">
    <input type="radio" name="type" autocomplete="off" v-bind:checked="selected.type=='mini'"> Room Service
  </label>
  
  <label class="btn btn-default" :class="{'active': selected.type=='delivery'}" style="width: 28%" v-on:click="selected.type='delivery'">
    <input type="radio" name="type" autocomplete="off" v-bind:checked="selected.type=='delivery'"> Delivery
  </label>
  
  <label class="btn btn-default" :class="{'active': selected.type=='pickup'}" style="width: 28%" v-on:click="selected.type='pickup'">
    <input type="radio" name="type" autocomplete="off" v-bind:checked="selected.type=='pickup'"> Pickup
  </label>


</div>

<br><br>

 
<div v-if="selected.address" class="btn-group" data-toggle="buttons" style="width: 100%; margin-bottom: 20px;" v-show="selected.type!='pickup'">


      <label class="btn btn-default" 
             v-for="ua in userAddresses" 
             :class="{'active': selected.address==$key}"
             v-bind:style="{width: percentAddress+'%'}" 
             v-on:click="selected.address=$key">
        <input type="radio" name="type" autocomplete="off" v-bind:checked="selected.address==$index"> @{{ ua }}

      </label>

</div>

<div style="width: 100%; margin-bottom: 20px;"  v-show="selected.type=='pickup'">

    <div style="margin-bottom:15px;">
     <?php /*   {!!  Form::select('city', \App\City::listByCountry('AE') , '1', ['id'=>'city', 'class'=>'form-control', 'v-model'=>'selected.city', 'style'=>'width:100%']); !!}
     */ ?>
     {!!  Form::select('city', [4=>'Dubai'] , '4', ['id'=>'city', 'class'=>'form-control', 'v-model'=>'selected.city', 'style'=>'width:100%']); !!}

    </div>
    
    <select style="width:100%" name="area" id="area" v-model="selected.area"></select> @{{selected.area}}
</div>

<div class="alert alert-warning" role="alert" v-if="!selected.address">
    <a href="/dashboard/address/create">Add an Address to order delivery</a>
</div>



<div class="btn-group" data-toggle="buttons" style="width: 99%">
  <label :class="['btn', 'btn-default',{'active': selected.time=='now'}]"
         style="width: 50%"
         v-on:click="selected.time='now'">
    <input type="radio" name="time" autocomplete="off" v-bind:checked="selected.time=='now'">Open Now
  </label>
  
  <label :class="['btn', 'btn-default',{'active': selected.time=='all'}]" style="width: 50%" v-on:click="selected.time='all'">
    <input type="radio" name="time" autocomplete="off" v-bind:checked="selected.time=='all'">All
  </label>
</div>

<br><br>

<?php /*


<div v-if="selected.address" style="width: 100%; margin-bottom: 20px;" v-show="selected.type!='pickup'">
    <div v-repeat="userAddresses" >
        <input type="radio" name="type" autocomplete="off" v-attr="checked: selected.address==$index" style="width: 18px; height: 18px;"> 
        <label>@{{ $value }}</label>
    </div>

</div>


<div>
    <input type="checkbox" id="openNow" style="width: 18px; height: 18px;"> &nbsp;
    <label for="openNow">Open Now</label>
</div>
*/ ?>



<div style="text-align: right;">
    <button type="button" class="btn btn-danger" v-on:click="updateStores">Update</button>
</div>

  <br>

  </div>
  <div class="col-sm-8 col-md-9">

    <div id="custom-search-input">
        <div class="input-group col-md-12">
            <input type="text" class="search-query form-control" placeholder="Filter..." v-model="searchText" />
            <span class="input-group-btn">
                <button class="btn btn-danger" type="button">
                    <span class=" glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
    </div>

    <div style="margin: 1em 0" class="alert alert-warning" role="alert" v-show="error_message!=''">@{{ error_message }}</div>


    <div style="padding: 2em;font-style: italic;" v-show="stores.length == 0">No stores matches your criteria.</div>

    <div style="padding-top: 4em; text-align: center" v-show="loading"><img src="/img/ajax-loader.gif"></div>

    <div v-show="!loading">@include('browse._store', array('vRepeat' => "s in stores | filterBy searchText"))</div>






  </div>
</div>


@stop

@section('footer')
<script type="text/javascript">

        function update_area(){
            if($("#city").val() == null ){
                helper_clear('#area');
            }else{
                $.ajax({
                  url: '/api/v1/country/AE/cities/' + $("#city").val() + '/areas/' ,
                  type: 'GET',
                  cache: true,
                  success: function(data) {
                    if(data.length == 0){
                        helper_clear('#area');
                    }else{
                        $('#area').select2({data: data});
                    }
                   },
                  error: function(e) { vm.error_message = "Some error occurred. Please try again."; }
                });
            }
        }

        function helper_clear(input){
            $(input).select2('val','');
            $(input).html(' ');
        }

        //$('#city').select2().on("change", update_area);
        $('#area').select2();

        update_area();
















    var vm = new Vue({
    el: '#defaultMainContainer',
    data:{
        userAddresses: {!! json_encode($user->listAddresses()) !!},
        selected:{'type':'mini', 'address':'', 'time':'now', 'cusine':'', 'city':'', 'area':''},
        stores: [],
        error_network : 0,
        error_message : '',
        loading : false,
        searchText : '',
    },

    ready: function(){
        var vars = [], hash;
            var q = document.URL.split('?')[1];
            if(q != undefined){
                q = q.split('&');
                for(var i = 0; i < q.length; i++){
                    hash = q[i].split('=');
                    vars.push(hash[1]);
                    vars[hash[0]] = hash[1];
                }
        }

        if("type" in vars) this.selected.type = vars["type"];
        if("address_id" in vars) this.selected.address = vars["address_id"];

        if(this.selected.address == ''){
          var that = this;
          //selects the first address
          Vue.nextTick(function () {
              var set=0
              for (var id in that.userAddresses) {
                  if(set==0){
                      that.selected.address = id;
                      set = 1;
                  }
              }
          })
        }

        
        if(this.selected.type == 'pickup') setTimeout(function(){ vm.updateStores(); }, 2000);
        else setTimeout(function(){ vm.updateStores(); }, 1000);

                        
                    


    },

    computed: {
      percentAddress: function(){

        var count = 0;
        for (var k in this.userAddresses) {
            if (this.userAddresses.hasOwnProperty(k)) {
               ++count;
            }
        }

        return (100/count).toFixed(2);

      },

    },

    methods:{
      updateStores: function(){
        this.loading = true;
        var that = this;
        $.ajax({
            type: "POST",
            url: "/dashboard/order/stores",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                type: this.selected.type,
                address: this.selected.address,
                time: this.selected.time,
                city: $("#city").val(),
                area: $("#area").val(),
            },
            timeout: 15000,
            dataType: 'json'
        })
        .done(function(data) { //update orders
            if(data["error"]==0){
                that.stores = data['stores'];
            }else{
              that.error_message = data['error_message'];
            }
            that.loading = false;
        })
        .fail( function(xhr, status, error) {
            that.error_message = "Some network error occured while trying to update the stores list.";
            that.loading = false;
        });








      },

    } // methods

  })

  vm.$watch('error_message', function (newVal, oldVal) {
    setTimeout(function(){ vm.error_message = ''; }, 5000);
  })






</script>
@stop