<!doctype html>
<html lang="en">
<!--begin::Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>AdminLTE v4 | Dashboard</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="title" content="AdminLTE v4 | Dashboard"/>
    <meta name="author" content="ColorlibHQ"/>
    <meta
        name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
        name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
        crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.css') }}"/>
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous"
    />
    <!-- jsvectormap -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
        crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<!--end::Head-->
<!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
@include('admin.layouts.main')
<!--begin::Script-->
<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"
></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"
></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="{{ asset('adminlte/js/adminlte.js') }}"></script>
<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script>
<!--end::OverlayScrollbars Configure-->
<!-- OPTIONAL SCRIPTS -->


<!--end::Script-->
</body>
<!--end::Body-->
</html>


@if(0)
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8"/>
        <title>Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
        <meta content="Coderthemes" name="author"/>
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

        <!-- jQuery -->
        <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
        {{--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}

        <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>


        <!-- third party css -->
        {{--    <link href="{{ asset('css/admin/vendor/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />--}}
        <!-- third party css end -->

        <!-- App css -->
        <link href="{{ asset('css/admin/icons.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/admin/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style"/>
        <link href="{{ asset('css/admin/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style"/>

        <!-- Thêm CSS cho Select2 -->
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"/>
        {{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />--}}
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
        {{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">--}}

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="loading" data-layout="detached"
          data-layout-config='{"leftSidebarCondensed":false,"darkMode":true, "showRightSidebarOnStart": false}'>

    <!-- Topbar Start -->
    @include('admin.layouts.header')
    <!-- end Topbar -->

    <!-- Start Content-->
    <div>

        <!-- Begin page -->
        <div class="wrapper">

            <!-- ========== Left Sidebar Start ========== -->
            @include('admin.layouts.sidebar')
            <!-- Left Sidebar End -->

            <div class="content-page">
                <div class="content">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title d-flex justify-content-between align-items-center">
                                    @yield('title')
                                </h4>
                            </div>
                        </div>

                    </div>
                    @yield('content')
                </div>
                <!-- End Content -->

                <!-- Footer Start -->
                @include('admin.layouts.footer')
                <!-- end Footer -->

            </div> <!-- content-page -->

        </div> <!-- end wrapper-->
    </div>
    <!-- END Container -->


    <!-- Right Sidebar -->
    @include('admin.layouts.rightbar')

    <!-- /Right-bar -->


    <!-- Toastr JS -->
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>--}}

    <!-- bundle -->
    <script src="{{ asset('js/admin/vendor.min.js') }}"></script>
    <script src="{{ asset('js/admin/app.min.js') }}"></script>

    <!-- third party js -->
    {{--    <script src="{{ asset('js/admin/vendor/apexcharts.min.js') }}"></script>--}}
    {{--    <script src="{{ asset('js/admin/vendor/jquery-jvectormap-1.2.2.min.js') }}"></script>--}}
    {{--    <script src="{{ asset('js/admin/vendor/jquery-jvectormap-world-mill-en.js') }}"></script>--}}
    <!-- third party js ends -->

    <!-- demo app -->
    {{--    <script src="{{ asset('js/admin/pages/demo.dashboard.js') }}"></script>--}}
    <!-- plugin js -->
    {{--    <script src="{{ asset('js/admin/vendor/dropzone.min.js') }}"></script>--}}
    <!-- init js -->
    <script src="{{ asset('js/main.js') }}"></script>
    {{--    <script src="{{ asset('js/admin/ui/component.fileupload.js') }}"></script>--}}
    {{--    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>--}}

    <!-- Select2 -->
    <script src="{{ asset('js/select2.min.js') }}"></script>
    {{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>--}}

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @stack('js')
    <!-- end demo js-->
    </body>

    </html>
@endif
