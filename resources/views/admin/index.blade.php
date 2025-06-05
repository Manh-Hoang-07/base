<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>AdminLTE v4 | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

@if(config('app.env') === 'local')
<!-- Development Optimization Tools -->
<script src="{{ asset('js/css-optimizer.js') }}"></script>
<script src="{{ asset('js/image-optimizer.js') }}"></script>
<script src="{{ asset('js/performance-monitor.js') }}"></script>
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
