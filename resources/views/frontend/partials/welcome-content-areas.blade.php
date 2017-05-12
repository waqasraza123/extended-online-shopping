<h1>{{ucwords($category)}} Mobiles</h1>
@include('partials.map-modal')
@foreach($mobiles->chunk(4) as $m)
    <div class="row">
        @foreach($m as $mobile)
            <div class="col-sm-6 col-md-3 product mobile animated pulse">
                <div class="white-block product-box">
                    <div class="white-block-border">
                        <a href="{{route('show-mobile', ['brand' => $mobile['mobile']->brand->name, 'id' => $mobile['mobile']->id])}}">
                            <div class="thumbnail image_outer">
                                <img width="120px" height="90px" src="{{$placeholderImage}}" data-src="{{$mobile['mobile']->image}}">
                            </div>
                        </a>
                        <div class="caption product-data">
                            <p class="text-left">{{$mobile['mobile']->brand->name}}</p>
                            <b class="margin-0">{{$mobile['mobile']->title}}</b><br>
                            <span class="margin-0 black"><i class="fa fa-money"></i> {!!$mobile['price'] == 999999999999 ? 'Not Available' : ' Rs - ' . $mobile['price']!!}</span><br>
                            <p class="black"><i class="fa  fa-location-arrow"></i> {!!$mobile['distance'] == 999999999999 ? "Unavailable Local" : " Approx " . $mobile['distance'] . " Km Away"!!}</p>
                            <p class="local_online_tags">
                                <button data-shop-lat="{{$mobile['mobile']->shop_lat}}" data-shop-long="{{$mobile['mobile']->shop_long}}" data-toggle="tooltip" data-placement="top" title="{{$mobile['location']}}" class="btn bg-green btn-xs waves-effect shop_map_modal "{{($mobile['available'] == null || $mobile['available'] == 'online') ? "disabled" : ""}}>Local</button>
                                <button data-toggle="tooltip" data-placement="top" title="Available on Online Vendors" class="btn bg-red btn-xs waves-effect "{{($mobile['available'] == null || $mobile['available'] == 'local') ? "disabled" : ""}}>Online</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach