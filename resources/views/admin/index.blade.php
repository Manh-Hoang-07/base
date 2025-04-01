<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
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
    <link href="{{ asset('css/admin/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/admin/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('css/admin/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

    <!-- Thêm CSS cho Select2 -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
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
    @include('admin.layouts.topbar')
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
