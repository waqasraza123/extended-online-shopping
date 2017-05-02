<!-- User Info -->
<div class="user-info">
    <div class="image">
        <img src="/theme/images/user.png" width="48" height="48" alt="User" />
    </div>
    <div class="info-container">
        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}}</div>
        <div class="email">{{Auth::user()->email_phone}}</div>
        <div class="btn-group user-helper-dropdown">
            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
            <ul class="dropdown-menu pull-right">
                <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                <li role="seperator" class="divider"></li>
                <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                <li role="seperator" class="divider"></li>
                <li><a href="javascript:void(0);"><i class="material-icons">input</i>Sign Out</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- #User Info -->
<!-- Menu -->
<div class="menu">
    <ul class="list">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active">
            <a href="/">
                <i class="material-icons">home</i>
                <span>Home</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0);" class="menu-toggle">
                <i class="material-icons">settings_cell</i>
                <span>Mobiles</span>
            </a>

            {{--submenu for mobile--}}
            <ul class="ml-menu">
                <li>
                    <a href="/products/mobile">All Mobiles</a>
                </li>
                <li>
                    <a href="/products/mobile/create">Add Mobile</a>
                </li>
                <li>
                    <a href="/products/mobile/create">Categories/Brands</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
<!-- #Menu -->
<!-- Footer -->
<div class="legal">
    <div class="copyright">
        <h4>Extended Online Shopping</h4>
        <a href="/">Buy it cheap and Better!</a>
    </div>
    <div class="version">
        <b>Version: </b> 1.0  - &copy; 2016 - <?php echo date('Y')?>
    </div>
</div>
<!-- #Footer -->