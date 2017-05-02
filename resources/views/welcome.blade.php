@extends('layouts.frontend')
@section('content')
    {{--@include('frontend.search-bar')--}}
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-20">
            <div>
                <div class="body">
                    @foreach($mobiles->chunk(4) as $m)
                        <div class="row">
                            @foreach($m as $mobile)
                                <a href="{{route('show-mobile', ['brand' => $mobile['mobile']->brand->name, 'id' => $mobile['mobile']->id])}}">
                                    <div class="col-sm-6 col-md-3 product mobile">
                                        {{--@if($mobile->discount == '0')
                                            <span class="label label-success invisible">{{$mobile->discount . ' Off'}}</span>
                                        @else
                                            <span class="label label-success">{{$mobile->discount . ' Off'}}</span>
                                        @endif--}}
                                        <div class="white-block product-box">
                                            <div class="white-block-border">
                                                <div class="thumbnail image_outer">
                                                    <img width="120px" height="90px" src="{{$placeholderImage}}" data-src="{{$mobile['mobile']->image}}">
                                                </div>
                                                <div class="caption product-data">
                                                    <p class="text-left">{{$mobile['mobile']->brand->name}}</p>
                                                    <b class="margin-0">{{$mobile['mobile']->title}}</b><br>
                                                    <span class="margin-0">{{$mobile['price'] == 999999999999 ? 'Not Available' : 'Rs - ' . $mobile['price']}}</span><br>
                                                    {{--<b class="margin-0">{{$mobile['location']}}</b>--}}
                                                    {{--<p>
                                                        @if($mobile->old_price != '0' && $mobile->old_price != null)<strike><small class="small-font">Rs {{$mobile->old_price}}</small></strike>, @endif Rs {{$mobile->current_price}}
                                                    </p>--}}
                                                    <p>
                                                        <button data-toggle="tooltip" data-placement="top" title="{{$mobile['location']}}" class="btn bg-blue btn-xs waves-effect "{{$mobile['location'] == null ? "disabled" : ""}}>Local</button>
                                                        <button data-toggle="tooltip" data-placement="top" title="Available on Online Vendors" class="btn bg-pink btn-xs waves-effect "{{($mobile['location'] == null && $mobile['price'] == 999999999999) ? "disabled" : ""}}>Online</button>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                    @if($mobiles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="pagination-wrapper"> {!! $mobiles->render() !!} </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="/theme/js/pages/ui/tooltips-popovers.js"></script>
@endsection