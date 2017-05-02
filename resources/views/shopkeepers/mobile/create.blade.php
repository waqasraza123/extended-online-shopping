@extends('layouts.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('partials.error-messages.error')
            @include('partials.error-messages.success')
            {!! Form::open(['url' => route('mobile.store'), 'class' => 'form-horizontal', 'files' => true, 'id' => 'add_mobile_form']) !!}
            @include('shopkeepers.mobile.add-methods')
            @include ('shopkeepers.mobile.form')
            {!! Form::close() !!}
            @include('shopkeepers.mobile.excel')
            @include('shopkeepers.mobile.bulk-add')
        </div>
    </div>
@endsection