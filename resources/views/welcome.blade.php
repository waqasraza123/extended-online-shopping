@extends('layouts.frontend')
@section('content')
    @include('frontend.search-bar')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-20">
            <div>
                <div class="body">
                    @foreach($mobiles->chunk(4) as $m)
                        <div class="row">
                            @foreach($m as $mobile)
                                <a href="{{route('show-mobile', ['brand' => $mobile->brand->name, 'id' => $mobile->id])}}">
                                    <div class="col-sm-6 col-md-3 product mobile">
                                        {{--@if($mobile->discount == '0')
                                            <span class="label label-success invisible">{{$mobile->discount . ' Off'}}</span>
                                        @else
                                            <span class="label label-success">{{$mobile->discount . ' Off'}}</span>
                                        @endif--}}
                                        <div class="white-block product-box">
                                            <div class="white-block-border">
                                                <div class="thumbnail image_outer">
                                                    <img width="120px" height="90px" src="{{$placeholderImage}}" data-src="{{$mobile->image}}">
                                                </div>
                                                <div class="caption product-data">
                                                    <p class="text-left">{{$mobile->brand->name}}</p>
                                                    <b class="margin-0">{{$mobile->title}}</b>
                                                    {{--<p>
                                                        @if($mobile->old_price != '0' && $mobile->old_price != null)<strike><small class="small-font">Rs {{$mobile->old_price}}</small></strike>, @endif Rs {{$mobile->current_price}}
                                                    </p>--}}
                                                    {{--<p>
                                                        <button class="btn bg-blue btn-xs waves-effect "{{$mobile->local_online == 'l' ? "" : "disabled"}}>Local</button>
                                                        <button class="btn bg-pink btn-xs waves-effect "{{$mobile->local_online == 'o' ? "" : "disabled"}}>Online</button>
                                                    </p>--}}
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