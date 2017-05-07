@extends('layouts.frontend')

@section('content')
    @foreach($data->chunk(3) as $d)
        <div class="row">
        @foreach($d as $shop)
            <div class="item  col-xs-4 col-lg-4">
                <div class="thumbnail">
                    <img class="group list-group-image" src="http://placehold.it/400x250/000/fff" alt="" />
                    <div class="caption shop-description">
                        <h4 class="group inner list-group-item-heading">
                            {{$shop->shop_name}}
                        </h4>
                        <p><i class="fa fa-phone" style="margin-right: 5px;"></i>{{$shop->phone}}</p>
                        <p class="group inner list-group-item-text">
                            <i class="fa fa-map-marker" style="margin-right: 5px;"></i>{{$shop->location}}
                        </p>
                        <div class="row margin-top-20">
                            <div class="col-xs-12 col-md-6">
                                <p>
                                    {{$shop->products->count()}} Products
                                </p>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <a class="btn btn-success" href="{{route('shops.single', ['shopId' => $shop->id])}}">Visit Shop</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    @endforeach
@endsection