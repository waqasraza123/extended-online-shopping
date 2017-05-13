<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.header')
    @yield('head')
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        @include('layouts.partials.nav')
    </header>
    <div class="content-wrapper container-fluid" style="margin-left: 0">
        @include('partials.preloader')
        @include('layouts.partials.content-header')
        @yield('map')
        <div class="content">
            @include('layouts.partials.frontend.search-box')
            @yield('content')
        </div>
    </div>
    <footer class="main-footer" style="margin-left: 0">
        @include('layouts.partials.footer')
    </footer>
</div>
@include('layouts.partials.footer-scripts')
</body>
</html>
