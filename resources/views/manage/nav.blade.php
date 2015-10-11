<div style="height:20px">&nbsp;</div>

<ul class="nav nav-pills nav-stacked">
  <li role="presentation" {!! ($active=='general')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/general">General</a></li>
  <li role="presentation" {!! ($active=='menu')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/menu/">Menu Items</a></li>

  <li role="presentation" {!! ($active=='options')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/options/">Menu Options</a></li>
  <li role="presentation" {!! ($active=='coverage')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/coverage/">Delivery Coverage</a></li>
  <li role="presentation" {!! ($active=='timings')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/timings/">Delivery Timings</a></li>

  <li role="presentation" {!! ($active=='tags')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/tags/">Tags</a></li>

  <li role="presentation" {!! ($active=='users')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/users/">Users</a></li>



  <?php /*
  <li role="presentation"><a href="#">Users</a></li>
  <li role="presentation"><a href="#">Reports</a></li>
  <li role="presentation"><a href="#">Accounting</a></li>
  */ ?>
</ul>



@if ($store->status_listing == 'draft' )
<div style="margin-top:50px;">
  <form action="/manage/{{$store->slug}}/submitReview" method="POST">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <button class="btn btn-info btn-block">Submit to Review</button>
</div>
<div style="text-align:center; color:#47B7D9; margin:10px 0;">
  Your store is not published yet! After updating all of its information you have to submit it for review.
</div>
@endif

@if ($store->status_listing == 'review' )
<div style="text-align:center; color:#47B7D9; margin-top:50px;">
  Your store is submitted for review. You will recieve an email soon.
</div>
@endif
