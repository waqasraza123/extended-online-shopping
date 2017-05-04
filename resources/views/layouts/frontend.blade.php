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
    <aside class="main-sidebar">
        @include('layouts.partials.frontend-sidebar')
    </aside>
    <div class="content-wrapper container-fluid">
        @include('partials.preloader')
        <div class="content">
            @yield('content')
        </div>
    </div>
    <footer class="main-footer">
        @include('layouts.partials.footer')
    </footer>
</div>
@include('layouts.partials.footer-scripts')
</body>
</html>
