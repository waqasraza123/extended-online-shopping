@extends('layouts.frontend')
@section('page-header', 'Search Results')
@section('subheading', $resultsCount . ' Results For ' . $searchText)
@section('content')
    @include('partials.map-modal')
    <div class="row clearfix">
        <h4 class="text-center">
            @if(isset($marketLocation) && $marketLocationMatched == false)
                Not Available in {{$marketLocation}}
            @elseif(isset($marketLocation) && $marketLocationMatched == true)
                Available in {{$marketLocation}}
            @endif
        </h4>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-20">
            <div>
                <div class="body">
                    @forelse($mobiles->chunk(4) as $m)
                        <div class="row">
                            @foreach($m as $mobile)
                                <div class="col-sm-6 col-md-3 product mobile">
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
                                                <span class="margin-0 black"><i class="fa fa-money"></i> {{$mobile['price'] == 999999999999 ? 'Not Available' : 'Rs - ' . $mobile['price']}}</span><br>
                                                <p class="black"><i class="fa  fa-location-arrow"></i> {!!$mobile['distance'] == 999999999999 ? "Unavailable Local" : " Approx " . $mobile['distance'] . " Km Away"!!}</p>
                                                <p>
                                                    <button data-shop-lat="{{$mobile['mobile']->shop_lat}}" data-shop-long="{{$mobile['mobile']->shop_long}}" data-toggle="tooltip" data-placement="top" title="{{$mobile['location']}}" class="btn bg-green btn-xs waves-effect shop_map_modal "{{($mobile['available'] == null || $mobile['available'] == 'online') ? "disabled" : ""}}>Local</button>
                                                    <button data-toggle="tooltip" data-placement="top" title="Available on Online Vendors" class="btn bg-red btn-xs waves-effect "{{($mobile['available'] == null || $mobile['available'] == 'local') ? "disabled" : ""}}>Online</button>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="callout callout-danger">
                            <h4>
                                0 Results
                            </h4>
                            <p>
                                No Results Found for the Query.
                            </p>
                        </div>
                    @endforelse
                    @if($mobiles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="pagination-wrapper">
                            {!!
                            $mobiles->setPath('/search')
                            !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="/theme/js/pages/ui/tooltips-popovers.js"></script>
@endsection