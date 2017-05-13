@extends('layouts.app')
@section('page-header', 'User Profile')
@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.profile-card')
            <!-- About Me Box -->
            @include('user.partials.about')
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            @include('partials.error-messages.error')
            @include('partials.error-messages.success')
            @include('user.partials.update-profile')
        </div>
        <!-- /.col -->
    </div>
@endsection