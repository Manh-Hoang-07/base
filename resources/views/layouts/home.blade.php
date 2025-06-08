<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Trang Chủ') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Quick Fix Script (Load first to prevent errors) -->
    <script src="{{ asset('js/quick-fix.js') }}"></script>

    <!-- Preload Critical Resources -->
    <!-- System fonts don't need preloading -->

    <!-- Optimized System Fonts -->
    <style>
        :root {
            --font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            font-family: var(--font-family);
        }

        /* Preload critical system fonts */
        .font-preload {
            font-family: var(--font-family);
            visibility: hidden;
            position: absolute;
        }
    </style>

    <!-- Optimized CSS Bundle -->
    @vite(['resources/css/app.css'])

    <!-- All CSS moved to app.css for better performance -->

    @yield('styles')
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('home/posts*') ? 'active' : '' }}" href="{{ route('home.posts.index') }}">Bài Đăng</a>
                    </li>
                </ul>

                <!-- Tìm kiếm -->
                <form class="d-flex me-3" action="{{ route('home.posts.index') }}" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" name="search" placeholder="Tìm bài đăng..." aria-label="Search" value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- User menu -->
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Đăng Nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i> Đăng Ký
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.index') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Quản Lý
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-1"></i> Đăng Xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <h5>{{ config('app.name', 'Laravel') }}</h5>
                    <p class="text-muted">Trang web chia sẻ thông tin và kiến thức hữu ích cho cộng đồng. Mang đến những bài viết chất lượng và đáng tin cậy.</p>
                    <p class="text-muted">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
                <div class="col-md-3">
                    <h5>Liên Kết</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang Chủ</a></li>
                        <li class="mb-2"><a href="{{ route('home.posts.index') }}" class="text-decoration-none text-muted">Bài Đăng</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-decoration-none text-muted">Đăng Nhập</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-decoration-none text-muted">Đăng Ký</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Liên Hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@example.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> (123) 456-7890</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Đường ABC, Quận XYZ, TP. HCM</li>
                    </ul>
                    <div class="mt-3">
                        <a href="#" class="text-decoration-none me-2"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-decoration-none me-2"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-decoration-none me-2"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-decoration-none me-2"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Optimized JS Bundle -->
    @vite(['resources/js/app.js'])

    @yield('scripts')

    <!-- Performance optimizations -->
    <script>
        // Lazy load images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Preload next page on hover
        document.addEventListener('mouseover', function(e) {
            if (e.target.tagName === 'A' && e.target.hostname === window.location.hostname) {
                const link = document.createElement('link');
                link.rel = 'prefetch';
                link.href = e.target.href;
                document.head.appendChild(link);
            }
        }, { once: true });
    </script>
</body>
</html>
