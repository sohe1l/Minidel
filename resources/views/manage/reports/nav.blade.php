<ul class="nav nav-pills nav-stacked">

  <li role="presentation" {!! ($active=='orders')?'class="active"':'' !!}>
    <a href="/manage/{{$store->slug}}/reports/orders">Orders</a>
  </li>

  <li role="presentation" {!! ($active=='billing')?'class="active"':'' !!}>
    <a href="/manage/{{$store->slug}}/reports/billing">Billing</a>
  </li>

</ul>