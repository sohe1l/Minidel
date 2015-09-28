<div style="height:20px">&nbsp;</div>

<ul class="nav nav-pills nav-stacked">
  <li role="presentation" {!! ($active=='general')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/general">General</a></li>
  <li role="presentation" {!! ($active=='menu')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/menu/">Menu Items</a></li>

  <li role="presentation" {!! ($active=='options')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/options/">Menu Options</a></li>
  <li role="presentation" {!! ($active=='coverage')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/coverage/">Delivery Coverage</a></li>
  <li role="presentation" {!! ($active=='timings')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/timings/">Delivery Timings</a></li>

  <li role="presentation" {!! ($active=='users')?'class="active"':'' !!}><a href="/manage/{{$store->slug}}/users/">Users</a></li>



  <?php /*
  <li role="presentation"><a href="#">Users</a></li>
  <li role="presentation"><a href="#">Reports</a></li>
  <li role="presentation"><a href="#">Accounting</a></li>
  */ ?>
</ul>