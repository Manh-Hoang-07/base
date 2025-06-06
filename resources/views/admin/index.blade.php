<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'AdminLTE v4 | Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#007bff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Admin Panel">
    <meta name="msapplication-TileColor" content="#007bff">
    <meta name="msapplication-config" content="/browserconfig.xml">

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">

    <!-- PWA Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/images/icon-180x180.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/icon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/icon-16x16.png">
    <link rel="mask-icon" href="/images/safari-pinned-tab.svg" color="#007bff">

    <!-- Quick Fix Script (Load first to prevent errors) -->
    <script src="{{ asset('js/quick-fix.js') }}"></script>

    <!-- Development Mode Manager -->
    <script src="{{ asset('js/dev-mode-manager.js') }}"></script>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.css') }}"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

    <!-- Select2 CSS -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"/>

    @yield('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
@include('admin.layouts.main')

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/loading-utils.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('adminlte/js/adminlte.js') }}"></script>

<!-- Advanced Optimization Features -->
<script src="{{ asset('js/module-loader.js') }}"></script>
<script src="{{ asset('js/pwa-manager.js') }}"></script>
<script src="{{ asset('js/cache-manager.js') }}"></script>
<script src="{{ asset('js/font-optimizer.js') }}"></script>

<!-- CSS Cleanup (Always load to fix font errors) -->
<script src="{{ asset('js/css-cleanup.js') }}"></script>

@if(config('app.env') === 'local')
<!-- Lightweight Development Mode -->
<script src="{{ asset('js/dev-lightweight.js') }}"></script>
@else
<!-- Production Optimization Tools -->
<script src="{{ asset('js/css-optimizer.js') }}"></script>
<script src="{{ asset('js/image-optimizer.js') }}"></script>
<script src="{{ asset('js/performance-monitor.js') }}"></script>
<script src="{{ asset('js/websocket-manager.js') }}"></script>
@endif

@if(session('error'))
    <script>toastr.error("{{ session('error') }}");</script>
@endif

@if(session('success'))
    <script>toastr.success("{{ session('success') }}");</script>
@endif

@yield('scripts')
</body>
</html>
