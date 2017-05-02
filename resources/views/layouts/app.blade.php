<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.header')
    @yield('head')
</head>
<body class="theme-blue">
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Search Bar -->
<div class="search-bar">
    <div class="search-icon">
        <i class="material-icons">search</i>
    </div>
    <input type="text" placeholder="START TYPING...">
    <div class="close-search">
        <i class="material-icons">close</i>
    </div>
</div>
<!-- #END# Search Bar -->
<!-- Top Bar -->
@include('layouts.partials.nav')
<!-- #Top Bar -->

<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include('layouts.partials.sidebar')
        </div>
        <div class="col-md-9 content" style="margin-top:100px">
            @include('partials.preloader')
            @yield('content')
        </div>
    </div>
</div>
@include('layouts.partials.footer-scripts')
</body>
</html>
