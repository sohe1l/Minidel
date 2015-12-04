@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Zomato</li>
</ol>
@endsection

@section('content')


<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'zomato'))
  </div>
  <div class="col-md-9">
    <h2>Zomato Import<br>
        <small>Only to be done on empty store.</small>
    </h2>

    @include('errors.list') 

    {{ $resData->restaurant->name }} <br>

    {{ $resData->restaurant->location->address }} <Br>
    {{ $resData->restaurant->location->latitude }} <Br>
    {{ $resData->restaurant->location->longitude }} <br><br><br>

    <h3>Sections</h3>
    <table class="table">
    @foreach($sections as $key => $section)
      <tr>
        <td><input type="text" value="{{ $key }}" name="section_id"></td>
        <td><input type="text" value="{{ $section }}" name="section_title"></td>
      </tr>
    @endforeach
    </table>


    <h3>Options</h3>
    <table class="table">
    @foreach($options as $key => $option)
      <tr>
        <td><input type="text" value="{{ $key }}" name="option_id"></td>
        <td><input type="text" value="{{ $option['name'] }}" name="section_name"></td>
        <td><input type="text" value="{{ $option['min'] }}" name="section_min"></td>
        <td><input type="text" value="{{ $option['max'] }}" name="section_max"></td>
      </tr>

      @foreach($option['options'] as $optionItem)
        <tr>
          <td colspan=2><input type="text" value="{{ $optionItem['name'] }}" name="option_id"></td>
          <td colspan=2><input type="text" value="{{ $optionItem['price'] }}" name="option_id"></td>
        </tr>
      @endforeach
    @endforeach
    </table>



  </div>
</div>



@endsection

@section('footer')
@endsection