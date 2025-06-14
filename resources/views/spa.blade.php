<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Vue SPA') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('fonts/inter.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Meta tags for SEO -->
    <meta name="description" content="Laravel Vue.js SPA Application">
    <meta name="keywords" content="laravel, vue, spa, admin">
    <meta name="author" content="Your Company">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="Laravel Vue.js SPA Application">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ config('app.name') }}">
    <meta property="twitter:description" content="Laravel Vue.js SPA Application">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- CSS will be loaded by Vite through JS -->

    <!-- Additional CSS for loading state -->
    <style>
        /* Loading screen */
        #app-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            color: white;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            margin-top: 20px;
            font-size: 18px;
            font-weight: 500;
        }

        /* Hide loading when Vue is mounted */
        #app:not(:empty) + #app-loading {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Vue App Container -->
    <div id="app"></div>

    <!-- Loading Screen -->
    <div id="app-loading">
        <div class="loading-spinner"></div>
        <div class="loading-text">Đang tải ứng dụng...</div>
    </div>

    <!-- Global JavaScript Variables -->
    <script>
        // Make Laravel data available to Vue
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            baseUrl: 'http://web.local',
            apiUrl: 'http://web.local/api',
            user: @json(auth()->user()),
            locale: '{{ app()->getLocale() }}',
            environment: '{{ app()->environment() }}',
            routes: {
                api: {
                    admin: {
                        users: {
                            list: '{{ route('api.admin.public.users.list') }}',
                            create: '{{ route('api.admin.public.users.create') }}',
                            update: '{{ url('/api/v1/admin/users/update') }}', // Will be dynamic
                            delete: '{{ url('/api/v1/admin/users/delete') }}', // Will be dynamic
                            status: '{{ url('/api/v1/admin/users/status') }}', // Will be dynamic
                        },
                        roles: {
                            list: '{{ route('api.admin.public.roles.list') }}',
                            create: '{{ route('api.admin.public.roles.create') }}',
                            update: '{{ url('/api/v1/admin/roles/update') }}', // Will be dynamic
                            delete: '{{ url('/api/v1/admin/roles/delete') }}', // Will be dynamic
                        },
                        posts: {
                            list: '{{ route('api.admin.public.posts.list') }}',
                            create: '{{ route('api.admin.public.posts.create') }}',
                            update: '{{ url('/api/v1/admin/posts/update') }}', // Will be dynamic
                            delete: '{{ url('/api/v1/admin/posts/delete') }}', // Will be dynamic
                        },
                        permissions: {
                            list: '{{ route('api.admin.public.permissions.list') }}'
                        },
                        categories: {
                            list: '{{ route('api.admin.public.categories.list') }}',
                            create: '{{ route('api.admin.public.categories.create') }}',
                            update: '{{ url('/api/v1/admin/categories/update') }}', // Will be dynamic
                            delete: '{{ url('/api/v1/admin/categories/delete') }}', // Will be dynamic
                        },
                        series: {
                            list: '{{ route('api.admin.public.series.list') }}',
                            create: '{{ route('api.admin.public.series.create') }}',
                            update: '{{ url('/api/v1/admin/series/update') }}', // Will be dynamic
                            delete: '{{ url('/api/v1/admin/series/delete') }}', // Will be dynamic
                        },
                        dashboard: {
                            stats: '{{ route('api.admin.public.dashboard.stats') }}'
                        },
                        profile: {
                            info: '{{ route('api.admin.public.profile.info') }}',
                            update: '{{ route('api.admin.public.profile.update') }}',
                            changePassword: '{{ route('api.admin.public.profile.change-password') }}'
                        }
                    }
                }
            }
        };

        // Configure Axios defaults
        window.axios = window.axios || {};
        if (window.axios.defaults) {
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
        }
    </script>

    <!-- Vite JavaScript -->
    @vite(['resources/js/app-vue.js'])

    <!-- Remove loading screen after timeout -->
    <script>
        setTimeout(function() {
            const loading = document.getElementById('app-loading');
            if (loading) {
                loading.style.display = 'none';
            }
        }, 5000); // 5 seconds timeout
    </script>
</body>
</html>
