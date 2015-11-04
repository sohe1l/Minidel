@extends('layouts.default')

@section('breadcrumb')
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li class="active">Terms of Service</li>
</ol>
@endsection

@section('content')




<h2>About minidel.com</h2>

<h4>For Individuals</h4>
<div>
    Minidel is an online delivery service that enables customers to place orders using web or mobile app. Customers are required to enter their delivery address once in the system and it will be sent to the stores everytime automatically. This service will help everyone hassel of keeping different menu and phone numbers of eaech individual store.
</div>



<h4>For Store Managers</h4>
<div>
    Minidel will create an online ordering service for stores. It will dedicate a personal page for each store and it will include different information such as operating hours, menu, location and photos. Cusomers can view the store information and place orders directly.
    <a href="/manage/">Click here to create your store</a>.
</div>



@stop
