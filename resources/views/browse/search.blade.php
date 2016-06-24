@extends('layouts.default')


@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/">Home</a></li>
    <li><a href="/browse/">Browse</a></li>
    <li class="active">Search</li>
  </ol>
@endsection



@section('content')

<div class="row">
  <div class="col-md-3">
    <h4 style="margin-top: 0;">Filters</h4>

    <div class="form-group">
      <b>Type of Store:</b>
      <a v-show="selected.store !=''" v-on:click="selected.store = ''"><span class="glyphicon glyphicon-remove"></span></a>
      <select v-select="selected.store" style="width:100%;" options="selects.store"></select>
    </div>

    <div class="form-group">
    <b>Cuisine:</b>
    <a v-show="selected.cuisine !=''" v-on:click="selected.cuisine = ''"><span class="glyphicon glyphicon-remove"></span></a>
    <select v-select="selected.cuisine" style="width:100%;" options="selects.cuisine"></select>
    </div>

    <div class="form-group">
    <b>Dish:</b>
    <a v-show="selected.dish !=''" v-on:click="selected.dish = ''"><span class="glyphicon glyphicon-remove"></span></a>
    <select v-select="selected.dish" style="width:100%;" options="selects.dish"></select>
    </div>

    <div class="form-group">
    <b>Feature:</b>
    <a v-show="selected.feature !=''" v-on:click="selected.feature = ''"><span class="glyphicon glyphicon-remove"></span></a>
    <select v-select="selected.feature" style="width:100%;" options="selects.feature"></select>
    </div>
  </div>
  <div class="col-md-9"> 


   <div id="custom-search-input">
        <div class="input-group col-md-12">
            <input type="text" class="search-query form-control" name="q" placeholder="Search" v-model="searchQuery" debounce="500" />
            <span class="input-group-btn">
                <button class="btn btn-danger" type="button">
                    <span class=" glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
    </div>

    <div class="alert alert-danger" v-show="networkErr == 1" role="alert">Conection Error! Please make sure you are connected to the internet.</div>

    <div class="alert alert-warning" v-show="stores.length == 0" role="alert">No stores matches your criteria</div>

    <div style="padding-top: 4em; text-align: center" v-show="loading"><img src="/img/ajax-loader.gif"></div>

    <div v-show="!loading">
      

      <div class="listing storeListing clearfix" v-for="s in stores">
          <div class="col-sm-2">
            <a href="/@{{s.slug}}/order">
              <img v-bind:src="s.logo?'/img/logo/'+s.logo:'/img/logo/placeholder.svg'" class="img-responsive hidden-xs">
              <img v-bind:src="s.cover?'/img/cover/'+s.cover:'/img/cover/placeholder.svg'" class="img-responsive visible-xs">
            </a>
          </div>
          <div class="col-sm-10">
              <div class="title"><a href="/@{{s.slug}}/order">@{{ s.name }}</a></div>
              <div>
                  <span class="label label-success">@{{ s.is_open == 'true'?'Open Now':''  }}</span>
              </div>
              <div v-if="s.info">@{{ s.info.substr(0, 150) }}@{{ (s.info.length>150)?'...':'' }} </div>
          </div>
      </div>



    </div>

  </div>

</div>


@endsection
@section('footer')
<script type="text/javascript">

  Vue.directive('select', {
    twoWay: true,
    bind: function () {
      var optionsData
      var optionsExpression = this.el.getAttribute('options')
      if (optionsExpression) {
        optionsData = this.vm.$eval(optionsExpression)
      }
      var self = this
      $(this.el)
        .select2({
          data: optionsData
        })
        .on('change', function () {
          self.set(this.value)
        })
    },
    update: function (value) {
      $(this.el).val(value).trigger('change')
    },
    unbind: function () {
      $(this.el).off().select2('destroy')
    }
  })



    var vm = new Vue({
    el: '#defaultMainContainer',
    data:{
        selects: {
          'store': {!! json_encode(\App\Tag::tagList2('store')) !!},
          'cuisine': {!! json_encode(\App\Tag::tagList2('cuisine')) !!},
          'dish': {!! json_encode(\App\Tag::tagList2('dish')) !!},
          'feature': {!! json_encode(\App\Tag::tagList2('feature')) !!}
        },
        selected:{'store':'', 'cuisine':'', 'dish':'', 'feature':''}, // tags
        stores: {!! json_encode($stores) !!},
        searchQuery: '{{ $searchQuery }}',
        loading : false,
        networkErr: 0,
    },

    ready: function(){
                        
    

    },

    computed: {
      percentAddress: function(){

      },

    },

    methods:{
      updateStores: function(){
        var that = this;

        this.loading = true;

        $.ajax({
            type: "POST",
            url: "/search/",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                q: this.searchQuery,
                tags: JSON.stringify(this.selected),
            },
            timeout: 15000,
            dataType: 'json'
        })
        .done(function(data) { //update orders
            that.loading = false;
            that.networkErr = 0;

            if(data["error"]==0){
                that.stores = data['stores'];
            }else{
                alert(data['error_message']);
            }
        })
        .fail(function(data){
          that.loading = false;
          that.networkErr = 1;
        });

      },

    } // methods

  })

  vm.$watch('searchQuery', function (newVal, oldVal) { this.updateStores(); })
  vm.$watch('selected.store', function (newVal, oldVal) { this.updateStores(); })
  vm.$watch('selected.cuisine', function (newVal, oldVal) { this.updateStores(); })
  vm.$watch('selected.dish', function (newVal, oldVal) { this.updateStores(); })
  vm.$watch('selected.feature', function (newVal, oldVal) { this.updateStores(); })



</script>
@stop