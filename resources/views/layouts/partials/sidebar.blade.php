<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="/uploads/user.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{{Auth::user()->name}}</p>
            {{--<p>{{Auth::user()->email_phone}}</p>--}}
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li>
            <a href="/products/mobile">
                <i class="fa fa-mobile"></i> <span>All Mobiles</span>
            </a>
        </li>
        <li>
            <a href="/products/mobile/create">
                <i class="fa fa-plus"></i> <span>Add Mobile</span>
            </a>
        </li>
        <li>
            <a href="/products/mobile/create">
                <i class="fa fa-envelope"></i> <span>Categories/Brands</span>
            </a>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-money"></i> <span>Sales</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{route('user-sales', ['userId' => Auth::user()->id])}}"><i class="fa  fa-bar-chart-o"></i> View Sales</a></li>
                <li><a href="../../index2.html"><i class="fa fa-cart-arrow-down"></i> Sell Product</a></li>
            </ul>
        </li>
    </ul>
</section>