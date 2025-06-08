<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'AdminLTE v4 | Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- DNS prefetch for faster CDN loading -->
    <link rel="dns-prefetch" href="//code.jquery.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

    <!-- Preload critical resources -->
    <link rel="preload" href="https://code.jquery.com/jquery-3.7.1.min.js" as="script">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" as="script">

    <!-- Critical CSS - load synchronously -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}"/>

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

        /* Select2 Fixes */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--bootstrap-5 .select2-selection {
            border: 1px solid #e0e6ed;
            border-radius: 6px;
            min-height: 38px;
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            height: 38px;
            line-height: 38px;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-left: 12px;
            padding-right: 20px;
        }

        .select2-dropdown {
            border: 1px solid #e0e6ed;
            border-radius: 6px;
            z-index: 9999 !important;
        }

        /* Fix Select2 dropdown positioning and overflow */
        .select2-container--open .select2-dropdown {
            z-index: 9999 !important;
        }

        .select2-container--open .select2-dropdown--below {
            border-top: none;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .select2-container--open .select2-dropdown--above {
            border-bottom: none;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        /* Ensure dropdown doesn't overflow container */
        .select2-container {
            position: relative;
            z-index: 1;
        }

        .select2-dropdown {
            max-width: 100%;
            word-wrap: break-word;
        }

        .select2-results__option {
            padding: 8px 12px;
            word-wrap: break-word;
            white-space: normal;
        }

        /* Menu sidebar fixes */
        .sidebar-menu .nav-item.menu-open > .nav-link .nav-arrow {
            transform: rotate(90deg);
            transition: transform 0.2s ease;
        }

        .sidebar-menu .nav-link .nav-arrow {
            transition: transform 0.2s ease;
        }

        /* Important: Force submenu visibility rules */
        .sidebar-menu .nav-treeview {
            display: none !important;
            overflow: hidden;
            background-color: rgba(0,0,0,0.1);
            margin-left: 0.5rem;
        }

        .sidebar-menu .nav-item.menu-open > .nav-treeview {
            display: block !important;
        }

        /* Debug: Add border to see submenu */
        .sidebar-menu .nav-treeview {
            border-left: 2px solid #007bff;
            padding-left: 0.5rem;
        }

        /* Ensure parent menu items are clickable */
        .sidebar-menu .nav-item > .nav-link {
            cursor: pointer;
        }

        /* Bootstrap Icons fallback */
        .bi::before {
            font-family: "bootstrap-icons" !important;
        }

        /* Form container overflow fixes */
        .form-group, .mb-3 {
            position: relative;
            overflow: visible;
        }

        .card-body {
            overflow: visible;
        }

        .container-fluid {
            overflow: visible;
        }

        /* Specific fixes for Select2 in modals or constrained containers */
        .modal .select2-container {
            z-index: 10060;
        }

        .modal .select2-dropdown {
            z-index: 10061;
        }
    </style>

    <!-- Non-critical CSS - load async -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

    <!-- Fallback for browsers that don't support preload -->
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </noscript>

    <!-- Critical JS - load early -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @yield('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
@include('admin.layouts.main')

<!-- Scripts -->
<!-- Critical JS - already loaded in head -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>

<!-- Admin Custom JS -->
<script>
    // Admin Panel JavaScript
    $(document).ready(function() {
        // Select2 will be initialized by main.js

        // Initialize tooltips
        if (typeof bootstrap !== 'undefined') {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

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

        // Initialize sidebar menu
        initializeSidebarMenu();

        // Fallback: Initialize menu again after a delay to ensure AdminLTE is loaded
        setTimeout(initializeSidebarMenu, 500);
    });

    // Global AJAX setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Sidebar menu functionality - Optimized
    function initializeSidebarMenu() {
        // Remove AdminLTE's default treeview behavior
        $('.sidebar-menu').removeAttr('data-lte-toggle');
        $('.sidebar-menu .nav-link').off('click');

        // Handle menu toggle
        $('.sidebar-menu .nav-link').on('click', function(e) {
            const $link = $(this);
            const $parent = $link.closest('.nav-item');
            const $submenu = $parent.find('> .nav-treeview');

            // If this is a parent menu item (has submenu)
            if ($submenu.length > 0) {
                e.preventDefault();
                e.stopPropagation();

                const isOpen = $parent.hasClass('menu-open');

                // Close all other menus first
                $('.sidebar-menu .nav-item.menu-open').not($parent).removeClass('menu-open').find('.nav-treeview').hide();

                // Toggle current menu
                if (isOpen) {
                    $parent.removeClass('menu-open');
                    $submenu.slideUp(200);
                } else {
                    $parent.addClass('menu-open');
                    $submenu.show().slideDown(200);
                }
            }
        });
    }
</script>



<!-- Check admin functions after page load -->
<script>
// Wait for all scripts to load then check functions
window.addEventListener('load', function() {
    setTimeout(function() {
        // Only define fallback if functions are still missing
        if (typeof window.deleteItem === 'undefined') {
            window.deleteItem = async function(id, url = null, message = 'Bạn có chắc chắn muốn xóa?', reloadCallback = null) {
                if (confirm(message)) {
                    if (!url) {
                        const currentPath = window.location.pathname;
                        const pathParts = currentPath.split('/');
                        if (pathParts.includes('admin')) {
                            const module = pathParts[pathParts.length - 2];
                            url = `/api/v1/admin/${module}/delete/${id}`;
                        }
                    }

                    try {
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            if (typeof toastr !== 'undefined') {
                                toastr.success(data.message || 'Xóa thành công!');
                            } else {
                                alert(data.message || 'Xóa thành công!');
                            }

                            if (reloadCallback && typeof reloadCallback === 'function') {
                                setTimeout(() => {
                                    reloadCallback();
                                }, 500);
                            } else {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            }
                        } else {
                            if (typeof toastr !== 'undefined') {
                                toastr.error(data.message || 'Có lỗi xảy ra khi xóa!');
                            } else {
                                alert(data.message || 'Có lỗi xảy ra khi xóa!');
                            }
                        }
                    } catch (error) {
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Có lỗi xảy ra khi xử lý yêu cầu!');
                        } else {
                            alert('Có lỗi xảy ra khi xử lý yêu cầu!');
                        }
                    }
                }
            };
        }
    }, 1000); // Wait 1 second for all scripts to load
});
</script>

<!-- Non-critical JS - load deferred -->
<script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script async src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<!-- Admin Actions JS - Load deferred -->
<script defer src="{{ asset('js/admin-actions.js') }}"></script>

<!-- Main JS for Select2 autocomplete - Load deferred -->
<script defer src="{{ asset('js/main.js') }}"></script>

@if(session('error'))
    <script>toastr.error("{{ session('error') }}");</script>
@endif

@if(session('success'))
    <script>toastr.success("{{ session('success') }}");</script>
@endif

@yield('scripts')
</body>
</html>
