@extends('layouts.frontend')

@section('content')
    @foreach($data->chunk(3) as $d)
        <div class="row">
        @foreach($d as $shop)
            <div class="item  col-xs-4 col-lg-4">
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-aqua-active" style="background-color: #333 !important;">
                        <h3 class="widget-user-username">{{$shop->shop_name}}</h3>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="{{isset($shop->user->image) ? $shop->user->image: asset('/user.png')}}" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{$shop->sales()->count()}}</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{$shop->user->name}}</h5>
                                    <span class="description-text">OWNER</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">{{$shop->products()->count()}}</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <p class="text-center">
                        <a href="{{route('shops.single', ['shopId' => $shop->id])}}" class="btn btn-success btn-sm margin-bottom-20">Visit Shop</a>
                    </p>
                </div>
            </div>
        @endforeach
        </div>
    @endforeach
@endsection