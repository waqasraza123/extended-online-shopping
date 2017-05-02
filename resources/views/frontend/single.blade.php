@extends('layouts.frontend')
@section('head')
    <link href="/theme/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
@endsection
@section('content')
    {{--@include('frontend.search-bar')--}}
    <div class="row clearfix" style="margin-top: 20px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 product mobile">
                        <div class="white-block product-box">
                            <div class="white-block-border">
                                <div class="thumbnail image_outer">
                                    <img src="{{$placeholderImage}}" data-src="{{$mobile->image}}">
                                </div>
                                <div class="caption product-data">
                                    <p class="text-left" style="padding-bottom: 20px">{{$mobile->brand->name}}</p>
                                    <h2 class="margin-0">{{$mobile->title}}</h2>
                                    <hr style="color: #F1F5F7;">
                                    {{--<p>
                                        <button class="btn bg-blue btn-xs waves-effect "{{$mobile->local_online == 'l' ? "" : "disabled"}}>Local</button>
                                        <button class="btn bg-pink btn-xs waves-effect"{{$mobile->local_online == 'o' ? "" : "disabled"}}>Online</button>
                                    </p>--}}
                                    <h5>Exquisitely Crafted, Captivatingly Brilliant</h5>
                                    <p>
                                        Inspired by the works of glassblowers and artisan metalsmiths,
                                        the Samsung Galaxy S6 represents a seamless fusion of glass and metal.
                                        Make a breathtaking design statement with its beautiful curves and radiant glass
                                        surfaces that reflect a wide spectrum of dazzling colours.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix" style="margin-top: 20px;">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="body" style="background-color: #fff; padding: 20px">
                <div class="row" style="border: 1px solid whitesmoke">
                    <div class="col-sm-12 col-md-12">
                        <div class="table-responsive margin-top-20">
                            <table class="table table-condensed text-center table-bordered shops-info-table" style="vertical-align: middle !important;">
                                <thead>
                                <tr>
                                    <td><h5><i class="material-icons">image</i> Store Logo</h5></td>
                                    <td><h5><i class="material-icons">person</i> Shop Name</h5></td>
                                    <td><h5><i class="material-icons">attach_money</i> Price</h5></td>
                                    <td><h5><i class="material-icons">money_off</i> Discount</h5></td>
                                    <td><h5><i class="material-icons">link</i> Link</h5></td>
                                </tr>
                                </thead>
                                <tbody class="shops-info">
                                @foreach($data as $d)
                                    <tr>
                                        <td valign="middle">
                                            <img src="{{url('/').'/logo.jpg'}}" width="100px" height="50px">
                                        </td>
                                        <td valign="middle">
                                            <h3>{{\App\Shop::where('id', $d->shop_id)->first()->shop_name}}</h3>
                                        </td>

                                        <td valign="middle">
                                            {{--@if($d->old_price != '0')<strike><small class="small-font">Rs {{$d->old_price}}</small></strike>, @endif --}}<h3 class="col-red">Rs {{$d->current_price}}</h3>
                                        </td>
                                        <td class="col-teal" valign="middle"><h3>
                                            @if($d->current_price == '0' || $d->old_price == '0' || $d->old_price == '' || $d->current_price == '')
                                                0% Off
                                            @else
                                                {{number_format(((int)(str_replace(",", "", $d->old_price) - (int)str_replace(",", "", $d->current_price))/(int)str_replace(",", "", $d->old_price)) * 100, 2) .'% Off'}}
                                            @endif
                                        </h3></td>

                                        <td valign="middle">
                                            @if($d->local_online == 'l')
                                                <a class="btn btn-lg bg-teal local-store waves-effect" data-type="with-custom-icon" style="color: #fff; margin-top: 10px;">View Store Info</a>
                                            @else
                                                <a class="btn btn-lg bg-blue waves-effect" style="color: #fff; margin-top: 10px;" href="{{$d->link}}">Visit Store Link</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="/theme/plugins/sweetalert/sweetalert.min.js"></script>
@endsection