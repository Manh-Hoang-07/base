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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Admin Custom CSS -->
    <style>
        /* Admin Panel Custom Styles */
        .app-sidebar {
            background: #343a46 !important;
        }

        .sidebar-menu .nav-link {
            color: #c2c7d0;
            transition: all 0.3s ease;
        }

        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background-color: #2c3e50;
            color: #ffffff;
        }

        .brand-link {
            color: #ffffff !important;
            text-decoration: none;
        }

        .brand-text {
            color: #ffffff !important;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #e0e6ed;
        }

        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>

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
                <a href="{{ route('admin.index') }}" class="brand-link">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- AdminLTE JS -->
    <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

    <!-- Admin Custom JS -->
    <script>
        // Admin Panel JavaScript
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Initialize CKEditor
            if (typeof CKEDITOR !== 'undefined') {
                $('.ckeditor').each(function() {
                    CKEDITOR.replace(this.id, {
                        height: 300,
                        filebrowserUploadUrl: '/admin/upload',
                        filebrowserUploadMethod: 'form'
                    });
                });
            }

            // Confirm delete actions
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                if (confirm('Bạn có chắc chắn muốn xóa?')) {
                    $(this).closest('form').submit();
                }
            });

            // Auto-hide alerts
            $('.alert').delay(5000).fadeOut();
        });

        // Global AJAX setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

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
