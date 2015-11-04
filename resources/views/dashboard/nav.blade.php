<ul class="nav nav-pills nav-stacked">

  <li role="presentation" {!! ($active=='general')?'class="active"':'' !!}><a href="/dashboard/general">General</a></li>
  <li role="presentation" {!! ($active=='password')?'class="active"':'' !!}><a href="/dashboard/password">Change Password</a></li>
  <li role="presentation" {!! ($active=='address')?'class="active"':'' !!}><a href="/dashboard/address/">Delivery Addresses</a></li>

</ul>