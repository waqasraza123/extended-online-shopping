@extends('layouts.app')
@section('page-header', 'Sell Products')
@section('subheading', 'Search Items in Your Shop to Sell')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            @include('partials.error-messages.error')
            @include('partials.error-messages.success')
        </div>
    </div>
    <form method="GET" action="{{route('fetch-product', ['id' => Auth::user()->id])}}">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-sm-12">
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::select('mobile-id', $listing, null,
                        ['id' => 'sale-shop-items-search', 'class' => 'form-control',
                        'placeholder' => 'Search Mobiles', 'onchange' => 'this.form.submit()',
                        'value' => old('mobile-id')]) !!}
                    </div>
                </div>
            </div>
        </div>
    </form>
    @if(isset($mobile))

        <div class="row">
            <div class="col-sm-12 col-md-6 col-md-offset-3 product mobile">
                <div class="white-block product-box">
                    <div class="white-block-border">
                        <div class="thumbnail image_outer">
                            <img src="{{$placeholderImage}}" data-src="{{$mobile->mobile->image}}">
                        </div>
                        <div class="caption product-data">
                            <p class="text-left" style="padding-bottom: 20px">{{$mobile->mobile->brand->name}}</p>
                            <h2 class="margin-0">{{$mobile->mobile->title}}</h2>
                            <hr style="color: #F1F5F7;">
                            <form id="sell-product-form" method="POST" action="{{route('sell-product', ['productId' => $mobile->id])}}">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label class="control-label">Base Price</label>
                                                <input type="text" id="base-price" name="base_price" value="{{$mobile->current_price}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label class="control-label">Price</label>
                                                <input type="text" readonly id="price-of-item-being-sold" value="{{$mobile->current_price}}" name="price"
                                                class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label class="control-label">Quantity</label>
                                                <input id="quantity-of-item-being-sold" value="1" type="number" min="1" max="{{$mobile->stock}}" name="quantity"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="mobile_id" value="{{$mobile->id}}">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label class="control-label" style="visibility: hidden;">Submit</label>
                                                <input type="submit" value="{{$mobile->stock < 1 ? "Out Of Stock" : "Sell"}}"
                                                       class="btn btn-success {{$mobile->stock < 1 ? "" : "form-control"}}" {{$mobile->stock < 1 ? "disabled" : ""}}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection