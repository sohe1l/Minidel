@extends('layouts.default')

@section('breadcrumb')
    <ol class="breadcrumb hidden-xs">
      <li><a href="/">Home</a></li>
      <li><a href="/dashboard/">Dashboard</a></li>
      <li class="active">Address</li>
    </ol>
@endsection

@section('content')

<div class="row">
  <div class="col-md-3 hidden-xs">
  @include('dashboard.nav', array('active'=>'address'))
  </div>
  <div class="col-md-9">
  
    <h2>Delivery Addresses<br>
        <small>Manage your delivery addresses</small>
    </h2>

  @include('errors.list')


    <div class="row">
      <div class="col-lg-6">
        <a href="/dashboard/address/create" class="btn btn-default">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          New Address
        </a>
      </div><!-- /.col-lg-6 -->

      <div class="col-lg-6">
        
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->

    <br>
 
    @forelse ($user->addresses as $address)
      <div class="panel panel-default">
        <div class="panel-heading">
            {{ $address->name }}
            <span style="float:right">
                <a href="/dashboard/address/{{$address->id}}/edit" title="Edit">
                    <span class="glyphicon glyphicon-pencil"></span>
               </a>
                &nbsp;&nbsp;&nbsp;&nbsp;
              <a href="javascript:deleteAddress({{ $address->id }})">
                <span class="glyphicon glyphicon-remove"></span>
              </a>
            </span>
        </div>
        <div class="panel-body">
            {{ $address->city->name }}, {{ $address->area->name }}, {{ ($address->building)?$address->building->name:'' }},
            {{ $address->unit }}, {{ $address->info }}
        </div>
      </div>
    @empty
      <h4 style="text-align: center">To begin add a new address to your account!</h4>
    @endforelse















  </div>

</div>


<div id="insert">
</div>



@endsection

@section('footer')
  <script type="text/javascript">
      function deleteAddress(id){
        var r = confirm("Are you sure you want to delete the address ?");
        if (r == true) {
          $('#insert').html('<form action="/dashboard/address/'+id+'" method="post"><input type="hidden" name="_method" value="DELETE" />{!!Form::token()!!}</form>'); 
          $( "#insert form" ).submit();
        }
      }
  </script>
@endsection



