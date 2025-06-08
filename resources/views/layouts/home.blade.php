<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Trang Chủ') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Home Page Critical CSS -->
    <style>
        :root {
            --font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            font-family: var(--font-family);
            padding-top: 80px;
            background-color: #f8f9ff;
            color: #2c3e50;
            line-height: 1.6;
        }

        /* Critical navbar styles */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
            box-shadow: 0 2px 10px rgba(0,123,255,0.1);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: white !important;
            background-color: rgba(255,255,255,0.1);
            border-radius: 6px;
        }
    </style>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Home Custom CSS -->
    <style>
        /* Additional Home Styles */
        .post-card .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .post-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-title a {
            color: var(--dark-text);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .card-title a:hover {
            color: var(--primary-color);
        }

        .card-footer {
            background: transparent;
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        .badge {
            border-radius: 20px;
            font-weight: 500;
            padding: 6px 12px;
        }

        .badge-warning {
            background: linear-gradient(135deg, var(--warning-color), #e0a800);
            color: var(--dark);
        }

        .badge-success {
            background: linear-gradient(135deg, var(--success-color), #1e7e34);
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--dark-text);
        }

        .pagination {
            justify-content: center;
            margin-top: 3rem;
        }

        .page-link {
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,123,255,0.3);
        }

        .page-item.active .page-link {
            background: var(--primary-color);
            color: var(--white);
            box-shadow: 0 6px 15px rgba(0,123,255,0.3);
        }

        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: none;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .post-card .card-img-top {
                height: 180px;
            }

            .footer {
                text-align: center;
            }

            .footer .col-md-3,
            .footer .col-md-4,
            .footer .col-md-5 {
                margin-bottom: 2rem;
            }
        }
    </style>

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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Home Custom JS -->
    <script>
        // Home App JavaScript
        class HomeApp {
            constructor() {
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.initializeComponents();
                this.handlePageLoad();
            }

            setupEventListeners() {
                // Smooth scrolling for anchor links
                document.addEventListener('click', (e) => {
                    if (e.target.matches('a[href^="#"]')) {
                        e.preventDefault();
                        const target = document.querySelector(e.target.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });

                // Navbar scroll effect
                window.addEventListener('scroll', () => {
                    const navbar = document.querySelector('.navbar');
                    if (navbar) {
                        if (window.scrollY > 50) {
                            navbar.style.boxShadow = '0 4px 20px rgba(0,123,255,0.2)';
                        } else {
                            navbar.style.boxShadow = '0 2px 10px rgba(0,123,255,0.1)';
                        }
                    }
                });

                // Back to top button
                this.setupBackToTop();
            }

            setupBackToTop() {
                // Create back to top button
                const backToTop = document.createElement('button');
                backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
                backToTop.className = 'btn btn-primary back-to-top';
                document.body.appendChild(backToTop);

                // Show/hide on scroll
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 300) {
                        backToTop.style.display = 'block';
                    } else {
                        backToTop.style.display = 'none';
                    }
                });

                // Scroll to top on click
                backToTop.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            initializeComponents() {
                // Initialize tooltips
                this.initTooltips();
                // Initialize lazy loading
                this.initLazyLoading();
                // Initialize animations
                this.initAnimations();
            }

            initTooltips() {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            initLazyLoading() {
                if ('IntersectionObserver' in window) {
                    const imageObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
                                img.src = img.dataset.src;
                                img.classList.remove('lazy');
                                img.classList.add('loaded');
                                imageObserver.unobserve(img);
                            }
                        });
                    });

                    document.querySelectorAll('img[data-src]').forEach(img => {
                        imageObserver.observe(img);
                    });
                }
            }

            initAnimations() {
                // Fade in animation for cards
                if ('IntersectionObserver' in window) {
                    const animationObserver = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.style.opacity = '1';
                                entry.target.style.transform = 'translateY(0)';
                            }
                        });
                    });

                    document.querySelectorAll('.card, .post-item').forEach(el => {
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(20px)';
                        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                        animationObserver.observe(el);
                    });
                }
            }

            handlePageLoad() {
                // Remove loading class if exists
                document.body.classList.remove('loading');
                // Preload critical resources
                this.preloadResources();
            }

            preloadResources() {
                // Preload next page links on hover
                let preloadedLinks = new Set();

                document.addEventListener('mouseover', function(e) {
                    if (e.target.tagName === 'A' &&
                        e.target.hostname === window.location.hostname &&
                        !preloadedLinks.has(e.target.href)) {

                        const link = document.createElement('link');
                        link.rel = 'prefetch';
                        link.href = e.target.href;
                        document.head.appendChild(link);
                        preloadedLinks.add(e.target.href);
                    }
                });
            }
        }

        // Initialize app when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            window.HomeApp = new HomeApp();
        });
    </script>

    @yield('scripts')

    <!-- Admin Functions for compatibility -->
    <script>
        // Define deleteItem function for compatibility with admin components
        async function deleteItem(id, url = null, message = 'Bạn có chắc chắn muốn xóa?', reloadCallback = null) {
            if (confirm(message)) {
                // Nếu không có URL, tự động tạo API URL từ current path
                if (!url) {
                    const currentPath = window.location.pathname;
                    // Convert web path to API path
                    // /admin/users/index -> /api/v1/admin/users/delete/{id}
                    const pathParts = currentPath.split('/');
                    if (pathParts.includes('admin')) {
                        const module = pathParts[pathParts.length - 2]; // users, roles, etc.
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

                        // Reload API table if callback provided
                        if (reloadCallback && typeof reloadCallback === 'function') {
                            setTimeout(() => {
                                reloadCallback();
                            }, 500);
                        } else {
                            // Reload page if no callback
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
                    console.error('Error:', error);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Có lỗi xảy ra khi xử lý yêu cầu!');
                    } else {
                        alert('Có lỗi xảy ra khi xử lý yêu cầu!');
                    }
                }
            }
        }

        // Define toggleStatus function for compatibility
        async function toggleStatus(id, currentStatus, url = null, reloadCallback = null) {
            const action = currentStatus ? 'mở khóa' : 'khóa';
            const message = `Bạn có chắc chắn muốn ${action} người dùng này?`;

            if (confirm(message)) {
                if (!url) {
                    const currentPath = window.location.pathname;
                    const pathParts = currentPath.split('/');
                    if (pathParts.includes('admin')) {
                        const module = pathParts[pathParts.length - 2];
                        url = `/api/v1/admin/${module}/toggle-status/${id}`;
                    }
                }

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message || 'Cập nhật thành công!');
                        } else {
                            alert(data.message || 'Cập nhật thành công!');
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
                            toastr.error(data.message || 'Có lỗi xảy ra!');
                        } else {
                            alert(data.message || 'Có lỗi xảy ra!');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Có lỗi xảy ra khi xử lý yêu cầu!');
                    } else {
                        alert('Có lỗi xảy ra khi xử lý yêu cầu!');
                    }
                }
            }
        }

        // Export functions for global use
        window.deleteItem = deleteItem;
        window.toggleStatus = toggleStatus;
    </script>

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
