@extends('layouts.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('partials.error-messages.error')
            @include('partials.error-messages.success')
            {!! Form::model($mobile, [
                'method' => 'PATCH',
                'url' => ['/products/mobile', $mobile->data()->where('shop_id', $shopId)->first()->id],
                'class' => 'form-horizontal',
                'files' => true
            ]) !!}

            @include ('shopkeepers.mobile.update')

            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script>
        $("#storage").select2();
        $("#colors").select2();
    </script>
@endsection