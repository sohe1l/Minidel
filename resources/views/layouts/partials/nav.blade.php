<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#" style="padding:10px;">
        <img alt="Brand" style="height:30px" class="img-responsive" src="https://d.zmtcdn.com/images/logo/zlogo4r.png">
      </a>

      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapse-1">
        <span class="sr-only">Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

    </div>


    

    <div class="collapse navbar-collapse" id="collapse-1">
        <ul class="nav navbar-nav navbar-right">
            @if (\Auth::user())

                @if(\Session::get('hasRole', false) == true)
                    <button onclick="location.href='/manage';" type="button" class="btn btn-warning navbar-btn">Manage Store</button>
                @endif

                <button onclick="location.href='/dashboard';" type="button" class="btn btn-danger navbar-btn">Order</button>

                <li class="dropdown navbar-right" >

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding:10px;">
                        <img class="nav-gravatar" src="/img/user/{{ (\Auth::user()->gender=='F')?'female':'male' }}.jpg">

                        {{ \Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/dashboard/">Dashboard</a></li>
                        <li class="divider"></li>
                        <li><a href="/dashboard/orders">Orders</a></li>
                        <li><a href="/dashboard/address">Your Address</a></li>
                        <li class="divider"></li>
                        <li><a href="/auth/logout/">Logout</a></li>
                    </ul>
                </li>

            @else
                <li><a href="/auth/register/">Register</a></li>
                <li><a href="/auth/login/?redirect={{ Request::path() }}">Login</a></li>
            @endif
        </ul>
    </div>


  </div>
</nav>



<?php /*

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Nav navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">C.io</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"></li>
            </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/auth/login/">Login</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/auth/register/">Register</a></li>
                </ul>
        </div>
    </div>
</nav>


*/ ?>