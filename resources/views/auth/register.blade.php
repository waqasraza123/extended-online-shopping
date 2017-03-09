@extends('layouts.master')
@section('head')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAfUZQtcEqdyFVUf9VWvhqNd89Xtse6tbA&libraries=places"
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
                <form novalidate="novalidate" class="form-horizontal animated" id="user_register_form" role="form" method="POST" action="{{ url('/register') }}">
                    {{ csrf_field() }}

                    <div class="msg">Register</div>
                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                        <div class="form-line">
                            <input id="name" type="text" class="form-control" name="name" placeholder="Your Name" required autofocus value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">email</i>
                    </span>
                        <div class="form-line">
                            <input id="email_phone" type="text" class="form-control" name="email_phone" value="{{ old('shop_name') }}" required autofocus
                                   placeholder="Email Or Phone 0300-1234567">
                        </div>
                    </div>
                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                        <div class="form-line">
                            <input id="password" type="password" class="form-control" name="password" required
                                   placeholder="Password">
                        </div>
                    </div>
                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                        <div class="form-line">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                                   placeholder="Enter Password Again">
                        </div>
                    </div>
                    {{--<div class="form-group">
                        <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-pink">
                        <label for="terms">I read and agree to the <a href="javascript:void(0);">terms of usage</a>.</label>
                    </div>--}}

                    <button id="register_user" class="btn btn-block btn-lg bg-pink waves-effect submit" type="submit">SIGN UP</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="/login">Sign In?</a>
                    </div>

                </form>

                {{--***************************************--}}
                {{--get the information for the shop--}}
                {{--***************************************--}}
                <form novalidate="novalidate" class="form-horizontal wq-hide" id="shop_register_form" role="form" method="POST" action="{{ url('/register-shop') }}">
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
                                   placeholder="Shop Phone Number 0300-1234567">
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
                        <i class="material-icons">location_on</i>
                    </span>
                        <div class="form-line">
                            <input id="city" type="text" class="form-control" name="city"
                                   value="{{ old('city') }}" required autofocus
                                   placeholder="City">
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
                    <button id="register_shop" type="submit" class="btn btn-block btn-lg bg-pink waves-effect submit">
                        Register Shop
                    </button>
                </form>
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
    <script src="/theme/plugins/node-waves/waves.js"></script>
    <script src="/theme/plugins/jquery-validation/jquery.validate.js"></script>
@endsection