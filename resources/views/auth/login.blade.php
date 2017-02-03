@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">2
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        @include('partials.error-messages.error')
                        @include('partials.error-messages.success')
                        <form class="form-horizontal animated jello" role="form" method="POST" action="{{ url('/login') }}">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group-sm">
                                        <input id="email_phone" type="text" class="form-control" name="email_phone"
                                               value="{{ old('email_phone') }}" required autofocus
                                               placeholder="Phone Or Email">
                                    </div>
                                </div>
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="form-group-sm">
                                        <input id="password" type="password" class="form-control" name="password" required
                                               placeholder="Type your password.">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="bttn-fill bttn-sm bttn-primary loader">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
