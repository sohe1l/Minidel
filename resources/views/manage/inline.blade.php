@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/manage/">Manage</a></li>
  <li><a href="/manage/{{$store->slug}}">{{ $store->name }}</a></li>
  <li class="active">Inline Code</li>
</ol>
@endsection

@section('content')



<div class="row">
  <div class="col-md-3">
  @include('manage.nav', array('active'=>'inline'))
  </div>
  <div class="col-md-9">
    <h2>Inline Code<br>
        <small>Accept orders directly from your website.</small>
    </h2>
    @include('errors.list') 

    <div>Copy & Paste the following code and forward it to your website moderator to add it to your website.</div>

    <textarea class="form-control" style="height:100px;"><iframe src="http://www.minidel.com/{{$store->slug}}/order/inline" style="width:100%; height:900px;"><a href="http://www.minidel.com/{{$store->slug}}/order/inline" target="_blank">Online Order now from {{$store->name}}</a></iframe></textarea>

    <br>

    <h2><small>You can also link directly to your order page through the following order buttons.</small></h2>
    <table class="table" style="text-align: center">
      <tr>
        <td style="width:50%">
          <textarea class="form-control" style="width:100%;"><a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel1.jpg"></a></textarea>
        </td>
        <td style="width:50%">
          <a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel1.jpg"></a>
        </td>
      </tr>

      <tr>
        <td style="width:50%">
          <textarea class="form-control" style="width:100%;"><a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel2.jpg"></a></textarea>
        </td>
        <td style="width:50%">
          <a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel2.jpg"></a>
        </td>
      </tr>

      <tr>
        <td style="width:50%">
          <textarea class="form-control" style="width:100%;"><a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel3.png"></a></textarea>
        </td>
        <td style="width:50%">
          <a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel3.png"></a>
        </td>
      </tr>

      <tr>
        <td style="width:50%">
          <textarea class="form-control" style="width:100%;"><a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel4.png"></a></textarea>
        </td>
        <td style="width:50%">
          <a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel4.png"></a>
        </td>
      </tr>

      <tr>
        <td style="width:50%">
          <textarea class="form-control" style="width:100%;"><a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel5.jpg"></a></textarea>
        </td>
        <td style="width:50%">
          <a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel5.jpg"></a>
        </td>
      </tr>

      <tr>
        <td style="width:50%">
          <textarea class="form-control" style="width:100%;"><a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel6.png"></a></textarea>
        </td>
        <td style="width:50%">
          <a href="http://www.minidel.com/{{$store->slug}}/order/" target="_blank"><img title="Order now from {{$store->name}}" src="http://www.minidel.com/img/buttons/minidel6.png"></a>
        </td>
      </tr>
    </table>

  </div>
</div>











@endsection




@section('footer')
@endsection