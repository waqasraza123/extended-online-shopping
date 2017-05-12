@extends('layouts.master')
@section('head')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAfUZQtcEqdyFVUf9VWvhqNd89Xtse6tbA&libraries=places&region=pk"
            async defer>
    </script>
@endsection
@section('content')
    <div>
        <div class="signup-box">
            @include('partials.error-messages._include_error')
            @include('partials.error-messages._include_success')
            <div class="animated bounceIn card">
                <div class="body">
                    {{--***************************************--}}
                    {{--get the information for the shop--}}
                    {{--***************************************--}}
                    <form novalidate="novalidate" class="form-horizontal" id="shop_register_form" role="form" method="POST" action="{{ url('/register-shop') }}">
                        {{ csrf_field() }}
                        <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">shop</i>
                    </span>
                            <div class="form-line">
                                <input id="shop_name" type="text" class="form-control" name="shop_name"
                                       value="{{ old('shop_name') }}" required autofocus
                                       placeholder="Shop Name">
                            </div>
                        </div>
                        <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">phone</i>
                    </span>
                            <div class="form-line">
                                <input id="shop_phone" type="text" class="form-control" name="shop_phone"
                                       value="{{ old('shop_phone') }}" required autofocus
                                       placeholder="0300-1234567 or 051-1234567">
                            </div>
                        </div>

                        {{--location information--}}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">business</i>
                            </span>
                            <div class="form-line">
                                <input id="market_plaza" type="text" class="form-control" name="market_plaza"
                                       value="{{ old('market_plaza') }}" required autofocus
                                       placeholder="Market/Plaza Name">
                            </div>
                        </div>


                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">call_split</i>
                            </span>
                            <div class="form-line">
                                <input id="location" type="text" class="form-control" name="location"
                                       value="{{ old('location') }}" required autofocus
                                       placeholder="Market Location">
                            </div>
                        </div>
                        <input id="city" type="hidden" class="form-control" name="city"
                               value="Islamabad, Pakistan">
                        <input type="hidden" id="lat" value="" name="lat">
                        <input type="hidden" id="long" value="" name="long">
                        <button id="register_shop" type="submit" class="btn btn-block btn-lg bg-pink waves-effect submit">
                            Register Shop
                        </button>

                        {{--<p style="text-align: center; margin-top: 10px; text-decoration: underline" id="skip_shop_reg">Do it Later.</p>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        function init2() {
            var input = document.getElementById('location');
            var autocomplete = new google.maps.places.Autocomplete(input);

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                //document.getElementById('city2').value = place.name;
                document.getElementById('lat').value = place.geometry.location.lat();
                document.getElementById('long').value = place.geometry.location.lng();
            });
        }
        function init() {
            var input = document.getElementById('user-location');
            var autocomplete = new google.maps.places.Autocomplete(input);

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                //document.getElementById('city2').value = place.name;
                document.getElementById('lat').value = place.geometry.location.lat();
                document.getElementById('long').value = place.geometry.location.lng();
            });
        }
        google.maps.event.addDomListener(window, 'load', init);
        google.maps.event.addDomListener(window, 'load', init2);
    </script>
    <script src="/theme/plugins/node-waves/waves.js"></script>
    <script src="/theme/plugins/jquery-validation/jquery.validate.js"></script>
@endsection