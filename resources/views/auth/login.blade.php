@extends('layouts.master')

@section('content')
    <div class="login-box">
        <div class="card">
            @include('partials.error-messages.error')
            @include('partials.error-messages.success')
            <div class="body">
                <form class="animated bounceIn" id="login_form" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}
                    <div class="msg">Sign in</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input id="email_phone" type="text" class="form-control" name="email_phone"
                                   value="{{ old('email_phone') }}" required autofocus
                                   placeholder="Phone Or Email">
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password" type="password" class="form-control" name="password" required
                                   placeholder="Type your password.">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button id="login_button" class="btn btn-block bg-pink waves-effect submit"
                                    type="submit">SIGN IN</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="/register">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="{{ url('/password/reset') }}">
                                Forgot Your Password?
                            </a>
                        </div>
                    </div>
                </form>

                {{--select shop form--}}
                <form novalidate="novalidate" class="form-horizontal wq-hide animated bounceIn" id="select_shop_form" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}
                    <div class="msg">Select Shop</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">shop</i>
                        </span>
                        <div class="form-line">
                            <select name="shop_id" class="form-control" id="shop_id_selector"></select>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="/theme/plugins/node-waves/waves.js"></script>
    <script src="/theme/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="/theme/js/admin.js"></script>
@endsection