@extends('layouts.frontend')
@section('page-header', $mobiles->first()->shop->shop_name)
@section('subheading', 'Products')
@section('content')
    {{--@include('frontend.search-bar')--}}
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-20">
            <div>
                <div class="body">
                    @foreach($mobiles->chunk(4) as $m)
                        <div class="row">
                            @foreach($m as $mobile)
                                <a href="{{route('show-mobile', ['brand' => $mobile->mobile->brand->name, 'id' => $mobile->mobile->id])}}">
                                    <div class="col-sm-6 col-md-3 product mobile">
                                        <div class="white-block product-box">
                                            <div class="white-block-border">
                                                <div class="thumbnail image_outer">
                                                    <img width="120px" height="90px" src="{{$placeholderImage}}" data-src="{{$mobile->mobile->image}}">
                                                </div>
                                                <div class="caption product-data">
                                                    <p class="text-left">{{$mobile->mobile->brand->name}}</p>
                                                    <b class="margin-0">{{$mobile->mobile->title}}</b><br>
                                                    <span class="margin-0">{{$mobile->current_price == 999999999999 ? 'Not Available' : 'Rs - ' . $mobile->current_price}}</span><br>
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