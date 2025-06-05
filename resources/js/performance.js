// Performance Optimization Utilities

class PerformanceOptimizer {
    constructor() {
        this.init();
    }

    init() {
        this.setupLazyLoading();
        this.setupImageOptimization();
        this.setupPrefetching();
        this.setupServiceWorker();
        this.setupCriticalResourceHints();
    }

    // Lazy Loading Implementation
    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        
                        // Load the actual image
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            img.classList.add('loaded');
                        }
                        
                        // Load srcset if available
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                        }
                        
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            // Observe all lazy images
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });

            // Observe dynamically added images
            const mutationObserver = new MutationObserver(mutations => {
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) {
                            const lazyImages = node.querySelectorAll ? node.querySelectorAll('img[data-src]') : [];
                            lazyImages.forEach(img => imageObserver.observe(img));
                        }
                    });
                });
            });

            mutationObserver.observe(document.body, {
                childList: true,
                subtree: true
            });
        } else {
            // Fallback for older browsers
            document.querySelectorAll('img[data-src]').forEach(img => {
                img.src = img.dataset.src;
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                }
            });
        }
    }

    // Image Optimization
    setupImageOptimization() {
        // Convert images to WebP if supported
        if (this.supportsWebP()) {
            document.querySelectorAll('img').forEach(img => {
                if (img.src && !img.src.includes('.webp')) {
                    const webpSrc = img.src.replace(/\.(jpg|jpeg|png)$/i, '.webp');
                    
                    // Check if WebP version exists
                    this.checkImageExists(webpSrc).then(exists => {
                        if (exists) {
                            img.src = webpSrc;
                        }
                    });
                }
            });
        }

        // Add loading="lazy" to images
        document.querySelectorAll('img').forEach(img => {
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
            }
        });
    }

    // Prefetching for better navigation
    setupPrefetching() {
        let prefetchedLinks = new Set();
        
        // Prefetch on hover
        document.addEventListener('mouseover', (e) => {
            if (e.target.tagName === 'A' && 
                e.target.hostname === window.location.hostname &&
                !prefetchedLinks.has(e.target.href)) {
                
                this.prefetchPage(e.target.href);
                prefetchedLinks.add(e.target.href);
            }
        });

        // Prefetch visible links
        if ('IntersectionObserver' in window) {
            const linkObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const link = entry.target;
                        if (!prefetchedLinks.has(link.href)) {
                            this.prefetchPage(link.href);
                            prefetchedLinks.add(link.href);
                        }
                        linkObserver.unobserve(link);
                    }
                });
            });

            document.querySelectorAll('a[href^="/"], a[href^="' + window.location.origin + '"]').forEach(link => {
                linkObserver.observe(link);
            });
        }
    }

    // Service Worker for caching
    setupServiceWorker() {
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                    })
                    .catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    }

    // Critical Resource Hints
    setupCriticalResourceHints() {
        // Preload critical CSS
        const criticalCSS = document.querySelector('link[rel="stylesheet"]');
        if (criticalCSS) {
            const preloadLink = document.createElement('link');
            preloadLink.rel = 'preload';
            preloadLink.as = 'style';
            preloadLink.href = criticalCSS.href;
            document.head.insertBefore(preloadLink, criticalCSS);
        }

        // Preload critical fonts
        const fontUrls = [
            '/fonts/admin/Nunito-Regular.woff2',
            '/fonts/admin/Nunito-SemiBold.woff2',
            '/fonts/figtree-400.woff2',
            '/fonts/figtree-500.woff2'
        ];

        fontUrls.forEach(url => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'font';
            link.type = 'font/woff2';
            link.crossOrigin = 'anonymous';
            link.href = url;
            document.head.appendChild(link);
        });
    }

    // Utility Methods
    supportsWebP() {
        const canvas = document.createElement('canvas');
        canvas.width = 1;
        canvas.height = 1;
        return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    }

    checkImageExists(url) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = () => resolve(true);
            img.onerror = () => resolve(false);
            img.src = url;
        });
    }

    prefetchPage(url) {
        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = url;
        document.head.appendChild(link);
    }

    // Performance Monitoring
    measurePerformance() {
        if ('performance' in window) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    const metrics = {
                        dns: perfData.domainLookupEnd - perfData.domainLookupStart,
                        tcp: perfData.connectEnd - perfData.connectStart,
                        ttfb: perfData.responseStart - perfData.requestStart,
                        download: perfData.responseEnd - perfData.responseStart,
                        dom: perfData.domContentLoadedEventEnd - perfData.domContentLoadedEventStart,
                        total: perfData.loadEventEnd - perfData.navigationStart
                    };

                    console.log('Performance Metrics:', metrics);
                    
                    // Send to analytics if needed
                    // this.sendAnalytics(metrics);
                }, 0);
            });
        }
    }

    // Critical CSS Inlining
    inlineCriticalCSS() {
        const criticalCSS = `
            body { font-family: system-ui, -apple-system, sans-serif; }
            .loading { opacity: 0; }
            .navbar { position: fixed; top: 0; width: 100%; z-index: 1000; }
            .app-sidebar { position: fixed; left: 0; top: 0; width: 250px; height: 100vh; }
            .app-main { margin-left: 250px; margin-top: 60px; }
        `;

        const style = document.createElement('style');
        style.textContent = criticalCSS;
        document.head.insertBefore(style, document.head.firstChild);
    }
}

// Initialize Performance Optimizer
document.addEventListener('DOMContentLoaded', () => {
    window.PerformanceOptimizer = new PerformanceOptimizer();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PerformanceOptimizer;
}
