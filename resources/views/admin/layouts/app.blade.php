<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Admin Panel') }}</title>

    <!-- Primary Meta Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="Admin Panel"/>
    <meta name="description" content="Admin Dashboard Panel"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Preload Critical Resources -->
    <link rel="preload" href="{{ asset('fonts/admin/Nunito-Regular.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/admin/Nunito-SemiBold.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Critical CSS - Inline for faster loading -->
    <style>
        /* Critical CSS for above-the-fold content */
        @font-face {
            font-family: 'Nunito';
            src: url('{{ asset('fonts/admin/Nunito-Regular.woff2') }}') format('woff2'),
                 url('{{ asset('fonts/admin/Nunito-Regular.woff') }}') format('woff');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Nunito';
            src: url('{{ asset('fonts/admin/Nunito-SemiBold.woff2') }}') format('woff2'),
                 url('{{ asset('fonts/admin/Nunito-SemiBold.woff') }}') format('woff');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }

        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .loading {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .app-wrapper {
            min-height: 100vh;
        }

        /* Critical layout styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            z-index: 1000;
            background: #343a46;
            transition: all 0.3s ease;
        }

        .app-header {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 60px;
            z-index: 999;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .app-main {
            margin-left: 250px;
            margin-top: 60px;
            min-height: calc(100vh - 60px);
        }

        @media (max-width: 768px) {
            .app-sidebar {
                transform: translateX(-100%);
            }
            .app-header,
            .app-main {
                margin-left: 0;
                left: 0;
            }
            .sidebar-open .app-sidebar {
                transform: translateX(0);
            }
        }
    </style>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

    <!-- Select2 CSS -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"/>

    <!-- Main CSS Bundle -->
    @vite(['resources/css/admin.css'])

    <!-- Page Specific Styles -->
    @yield('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary loading">
    <!-- App Wrapper -->
    <div class="app-wrapper">
        @include('admin.layouts.header')

        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!-- Sidebar Brand -->
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}" class="brand-link">
                    <img src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}"
                         alt="Admin Logo"
                         class="brand-image opacity-75 shadow"
                         loading="lazy"/>
                    <span class="brand-text fw-light">{{ config('app.name', 'Admin') }}</span>
                </a>
            </div>
            @include('admin.layouts.sidebar')
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            @include('admin.layouts.pathway')

            <!-- App Content -->
            <div class="app-content">
                @yield('content')
            </div>
        </main>

        @include('admin.layouts.footer')
    </div>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

    <!-- AdminLTE JS -->
    <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('js/toastr.min.js') }}"></script>

    <!-- Select2 JS -->
    <script src="{{ asset('js/select2.min.js') }}"></script>

    <!-- CKEditor -->
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>

    <!-- JavaScript Bundle -->
    @vite(['resources/js/admin.js'])

    <!-- Page Specific Scripts -->
    @yield('scripts')

    <!-- Initialize App -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Remove loading class
            document.body.classList.remove('loading');

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Mobile sidebar toggle
            const sidebarToggle = document.querySelector('[data-lte-toggle="sidebar"]');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.body.classList.toggle('sidebar-open');
                });
            }
        });
    </script>
</body>
</html>
