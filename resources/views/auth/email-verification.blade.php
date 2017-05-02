@extends('layouts.master')

@section('content')
    <div>
        <div class="signup-box">
            @include('partials.error-messages._include_error')
            @include('partials.error-messages._include_success')
            <div class="animated bounceIn card">
                <div class="body">
                    {{--email token verification--}}
                    <form novalidate="novalidate" class="form-horizontal" method="POST" action="{{action('UserController@verify')}}">
                        {{ csrf_field() }}
                        <p class="text-center">Please check your email for verification token.</p>
                        <div class="input-group margin-top-20">
                            <span class="input-group-addon">
                                <i class="material-icons">lock_open</i>
                            </span>
                            <div class="form-line">
                                <input id="email_token" type="text" class="form-control" name="email_token" required autofocus
                                       placeholder="token">
                            </div>
                        </div>
                        <input type="hidden" name="email" value="{{$email}}">
                        <input type="submit" class="btn btn-block btn-lg bg-pink waves-effect submit" value="Verify">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="/theme/plugins/node-waves/waves.js"></script>
@endsection