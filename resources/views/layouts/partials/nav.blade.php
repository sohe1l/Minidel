<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="/">
        <img alt="Brand" style="height:30px" class="img-responsive" src="/img/logo.png">
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
                <li class="visible-xs"><a href="/manage/">Manage Stores</a></li>
                @endif
                <li class="visible-xs"><a href="/dashboard/order/">Order</a></li>
                <li class="visible-xs"><a href="/dashboard/orders">Orders</a></li>
                <li class="visible-xs"><a href="/dashboard/address">Your Address</a></li>
                <li class="visible-xs"><a href="/auth/logout/">Logout</a></li>
                


                @if(\Session::get('hasRole', false) == true)
                    <li class="hidden-xs"><a href="/manage" style="padding:6px 12px; color:white; margin-right: 5px;" class="btn btn-warning navbar-btn">Manage Store</a></li>
                @endif

                <li class="hidden-xs"><a href="/dashboard/order" style="padding:6px 12px; color:white; margin-right: 5px;" class="btn btn-danger navbar-btn">Order</a></li>

                <li class="dropdown" class="hidden-xs">

                    <a href="#" class="dropdown-toggle hidden-xs" data-toggle="dropdown">
                        <img class="nav-gravatar" src="/img/user/{{ (\Auth::user()->gender=='F')?'female':'male' }}.jpg">

                        {{ \Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu hidden-xs" role="menu">
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