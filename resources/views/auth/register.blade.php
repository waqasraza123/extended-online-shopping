@extends('layouts.app')
@section('head')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAfUZQtcEqdyFVUf9VWvhqNd89Xtse6tbA&libraries=places"
            async defer>
    </script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    @include('partials.error-messages._include_error')
                    @include('partials.error-messages._include_success')
                    <form class="form-horizontal animated jello" id="user_register_form" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        {{--name and phone number or email fields--}}
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group-sm">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus
                                           placeholder="Your Name">
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group-sm">
                                    <input id="email_phone" type="text" class="form-control" name="email_phone" value="{{ old('shop_name') }}" required autofocus
                                           placeholder="Email Or Phone 0300-1234567">
                                </div>
                            </div>
                        </div>
                        {{--password fields--}}
                        <div class="row">
                            <div class="form-group-sm">
                                <div class="col-md-6 col-md-offset-3">
                                    <input id="password" type="password" class="form-control" name="password" required
                                        placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group-sm">
                                <div class="col-md-6 col-md-offset-3">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                                           placeholder="Enter Password Again">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group-sm">
                                <button id="register_user" type="submit" class="bttn-fill bttn-sm bttn-primary center-block margin-top-20">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                    {{--***************************************--}}
                    {{--get the information for the shop--}}
                    {{--***************************************--}}
                    <form class="form-horizontal wq-hide" id="shop_register_form" role="form" method="POST" action="{{ url('/register-shop') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group-sm">
                                    <input id="shop_name" type="text" class="form-control" name="shop_name"
                                           value="{{ old('shop_name') }}" required autofocus
                                           placeholder="Shop Name">
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group-sm">
                                    <input id="shop_phone" type="text" class="form-control" name="shop_phone"
                                           value="{{ old('shop_phone') }}" required autofocus
                                           placeholder="Shop Phone Number 0300-1234567">
                                </div>
                            </div>
                        </div>

                        {{--location information--}}
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group-sm">
                                    <input id="market_plaza" type="text" class="form-control" name="market_plaza"
                                           value="{{ old('market_plaza') }}" required autofocus
                                           placeholder="Market/Plaza Name">
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group-sm">
                                    <input id="city" type="text" class="form-control" name="city"
                                           value="{{ old('city') }}" required autofocus
                                           placeholder="City">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group-sm">
                                    <input id="location" type="text" class="form-control" name="location"
                                           value="{{ old('location') }}" required autofocus
                                           placeholder="Complete Address of Shop">
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group-sm">
                                    <button id="register_shop" type="submit" class="bttn-fill bttn-sm bttn-primary center-block margin-top-20">
                                        Register Shop
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
    <script>
        function init() {
            var input = document.getElementById('location');
            var autocomplete = new google.maps.places.Autocomplete(input);
        }
        google.maps.event.addDomListener(window, 'load', init);
    </script>
@endsection