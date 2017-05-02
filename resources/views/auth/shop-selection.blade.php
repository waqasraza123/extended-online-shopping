@extends('layouts.master')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="login-box">
        <div class="card">
            @include('partials.error-messages.error')
            @include('partials.error-messages.success')
            <div class="body">
                {{--select shop form--}}
                <form action="{{ route('dashboard-post') }}" novalidate="novalidate" class="form-horizontal animated bounceIn" id="select_shop_form" role="form" method="POST">
                    {{ csrf_field() }}
                    <div class="msg">Select Shop</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">shop</i>
                        </span>
                        <div class="form-line">
                            {!! Form::select('shop_id', $shops, null, ['class' => 'form-control',
                            'id' => 'select-shop-dropdown']) !!}
                        </div>
                    </div>
                    <div class="text-center">
                        <button id="shop_id_selector_button" class="btn btn-block bg-pink waves-effect"
                                type="submit">PROCEED</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="/theme/plugins/node-waves/waves.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script>
        $("#select-shop-dropdown").select2()
    </script>
@endsection