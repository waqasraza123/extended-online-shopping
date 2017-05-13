<!-- Profile Image -->
<div class="box box-primary">
    <div class="box-body box-profile">
        <img height="100px" class="profile-user-img img-responsive img-circle" src="{{isset(Auth::user()->image) ? Auth::user()->image: asset('/user.png')}}" alt="User profile picture">

        <h3 class="profile-username text-center">{{Auth::user()->name}}</h3>

        <p class="text-muted text-center">{{Auth::user()->email_phone}}</p>

        <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
                <b>Products</b> <a class="pull-right">{{$currentShopProductsCount or 0}}</a>
            </li>
            <li class="list-group-item">
                <b>Shops</b> <a class="pull-right">{{Auth::user()->shops()->count()}}</a>
            </li>
        </ul>

        <a href="{{route('shops.single', ['shopId' => $shopId])}}" class="btn btn-primary btn-block"><b>Visit Shop</b></a>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->