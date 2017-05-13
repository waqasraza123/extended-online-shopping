@extends('layouts.app')
@section('page-header', 'Shop Settings')
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2 col-sm-12 col-xs-12">
            @include('partials.error-messages.error')
            @include('partials.error-messages.success')
            <div class="box box-success">
                <div class="box-header with-header">
                    <h3>Shop Settings</h3>
                </div>
                {{ Form::model($shop, ['class' => 'form-horizontal', 'route' => 'shop.settings.update', 'files' => true]) }}
                <div class="box-body">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Shop Name</label>

                        <div class="col-sm-10">
                            {{Form::text('shop_name', null, ['class' => 'form-control', 'placeholder' => 'Shop Name'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Phone Number</label>

                        <div class="col-sm-10">
                            {{Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone Number'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Market/Plaza Name</label>

                        <div class="col-sm-10">
                            {{Form::text('market_plaza', null, ['class' => 'form-control', 'placeholder' => 'Market or Plaza Name'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Shop Location</label>

                        <div class="col-sm-10">
                            {{Form::text('location', null, ['class' => 'form-control', 'placeholder' => 'Shop Location',
                            'id' => 'shop_location'])}}
                        </div>
                    </div>
                    <input type="hidden" name="city" value="Islamabad, Pakistan">
                    <input type="hidden" name="lat" id="lat" value="{{$shop->lat}}">
                    <input type="hidden" name="long" id="long" value="{{$shop->long}}">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success waves-effect">Update Settings</button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        function init() {
            var input = document.getElementById('shop_location');
            var autocomplete = new google.maps.places.Autocomplete(input);

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                //document.getElementById('city2').value = place.name;
                document.getElementById('lat').value = place.geometry.location.lat();
                document.getElementById('long').value = place.geometry.location.lng();
            });
        }
        google.maps.event.addDomListener(window, 'load', init);
    </script>
@endsection