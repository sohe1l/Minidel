@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/superadmin/">Super Admin</a></li>
    <li class="active">Stores</li>
  </ol>
@endsection

@section('content')

@include('errors.list')



<table class="table whiteBG">
    <tr>
        <th>Store Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Last Check</th>
        <th>Listing Status</th>
        <th>Working Status</th>
        <th>Actions</th>

    </tr>
  @foreach($stores as $store)
   <tr class="{{ ($store->status_listing!='published')?'warning':'' }} {{ ($store->status_working!='open')?'danger':'' }}">
    <td><a href="/{{$store->slug}}" target="_blank">{{$store->name}}</a></td>
    <td>{{$store->email }}</td>
    <td>{{$store->phone }}</td>
    <td>{{ \Carbon\Carbon::parse($store->last_check)->diffForHumans() }}</td>
    <td>{{$store->status_listing }}</td>
    <td>{{$store->status_working }}</td>
    <td>
        <a href="/manage/{{$store->slug}}/general">Manage</a>
        
        &nbsp;&nbsp;&nbsp;

        @if($store->status_listing == 'published')
            <a href="javascript:setStatus({{$store->id}},'draft')">Unpublish</a>  
        @endif

        @if($store->status_listing == 'review')
            <a href="javascript:setStatus({{$store->id}}, 'published')">Publish</a>  
        @endif
    </td>


   </tr>
@endforeach
</table>

<div style="text-align: center">
    {!! $stores->render() !!}
</div>


<div id="insert">
</div>

@endsection


@section('footer')
  <script type="text/javascript">
      function setStatus(store_id, status_listing){
        var r = confirm("Are you sure you want to set store listing status to " + status_listing + "?");
        if (r == true) {
          $('#insert').html('<form action="/superadmin/stores/updateStatus" method="post"><input type="hidden" name="_method" value="PUT" />{!!Form::token()!!}<input type="hidden" name="store_id" value="'+store_id+'"><input type="hidden" name="status_listing" value="'+status_listing+'"></form>'); 
          $( "#insert form" ).submit();
        }
      }

  </script>
@endsection

