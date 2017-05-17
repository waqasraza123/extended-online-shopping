<a href="/" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>QUICK PHONE</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>QUICK PHONE</b></span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li><a href="{{ route('shops.index') }}">All Shops</a></li>
            @if (Auth::guest())
                <li><a href="{{ url('/login') }}">My Dashboard</a></li>
                {{--<li><a href="{{ url('/login/shop') }}">My Shop</a></li>--}}
                <li><a href="{{ url('/register') }}">Register Shop</a></li>
            @else
                @if(Auth::check() && isset($outOfStock))
                <!-- Messages: style can be found in dropdown.less-->
                    {{--<li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">4</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 4 messages</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li><!-- start message -->
                                        <a href="#">
                                            <div class="pull-left">
                                                <img src="{{isset(Auth::user()->image) ? Auth::user()->image: asset('/user.png')}}" class="img-circle" alt="User Image">
                                            </div>
                                            <h4>
                                                Support Team
                                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                            </h4>
                                            <p>Why not buy a new awesome theme?</p>
                                        </a>
                                    </li>
                                    <!-- end message -->
                                </ul>
                            </li>
                            <li class="footer"><a href="#">See All Messages</a></li>
                        </ul>
                    </li>--}}
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">{{$outOfStock != null ? $outOfStock->count() : 0}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">{{$outOfStock != null ? $outOfStock->count() : 0}} Items Out of Stock.</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                   {{-- {{dd($outOfStock)}}--}}
                                    @forelse($outOfStock as $item)
                                        @if($loop->index > 5)
                                            @break
                                        @endif
                                        <li>
                                            <a href="{{route('mobile.show', ['id' => $item->mobile->id])}}">
                                                <img src="{{$item->mobile->image}}" width="20" height="20"> {{$item->mobile->title}}
                                            </a>
                                        </li>
                                    @empty
                                    @endforelse
                                </ul>
                            </li>
                            <li class="footer"><a href="{{route('mobile.out_of_stock')}}">View all</a></li>
                        </ul>
                    </li>
                    {{--<!-- Tasks: style can be found in dropdown.less -->
                    <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 9 tasks</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <h3>
                                                Design some buttons
                                                <small class="pull-right">20%</small>
                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="sr-only">20% Complete</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">View all tasks</a>
                            </li>
                        </ul>
                    </li>--}}
                @endif
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{isset(Auth::user()->image) ? Auth::user()->image: asset('/user.png')}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{Auth::user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{isset(Auth::user()->image) ? Auth::user()->image: asset('/user.png')}}" class="img-circle" alt="User Image">

                            <p>
                                {{Auth::user()->name}}
                                <small>{{Auth::user()->email_phone}}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="{{route('user.profile')}}">Profile</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="{{route('shop.settings')}}">Shop Settings</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="{{route('register-shop')}}">Register Shop</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('dashboard')}}" class="btn btn-default btn-flat">My Dashboard</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('/logout') }}"
                                   onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</nav>