@extends('layouts.frontend')
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div>
                <div class="body">
                    @foreach($mobiles->chunk(4) as $m)
                        <div class="row">
                            @foreach($m as $mobile)
                                <div class="col-sm-6 col-md-3 product mobile">
                                    @if($mobile->discount == '0')
                                        <span class="label label-success invisible">{{$mobile->discount . ' Off'}}</span>
                                    @else
                                        <span class="label label-success">{{$mobile->discount . ' Off'}}</span>
                                    @endif
                                    <div class="white-block product-box">
                                        <div class="white-block-border">
                                            <div class="thumbnail image_outer">
                                                <img src="{{$placeholderImage}}" data-src="{{$mobile->image}}">
                                            </div>
                                            <div class="caption product-data">
                                                <p class="text-left">{{$mobile->brand->name}}</p>
                                                <b class="margin-0">{{$mobile->title}}</b>
                                                <p>
                                                    @if($mobile->old_price != '0')<strike><small class="small-font">Rs {{$mobile->old_price}}</small></strike>, @endif Rs {{$mobile->current_price}}
                                                </p>
                                                <p>
                                                    <span class="badge bg-blue">Local</span>
                                                    <span class="badge bg-pink">Online</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <div class="pagination-wrapper"> {!! $mobiles->render() !!} </div>
                </div>
            </div>
        </div>
    </div>
@endsection