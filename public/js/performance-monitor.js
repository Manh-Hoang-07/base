/**
 * Performance Monitor - Track and optimize frontend performance
 * Monitors page load times, resource loading, and user interactions
 */

class PerformanceMonitor {
    constructor() {
        this.metrics = {};
        this.observers = [];
        this.init();
    }

    /**
     * Initialize performance monitoring
     */
    init() {
        this.measurePageLoad();
        this.measureResourceLoading();
        this.measureUserInteractions();
        this.setupPerformanceObserver();
        this.monitorMemoryUsage();
    }

    /**
     * Measure page load performance
     */
    measurePageLoad() {
        if ('performance' in window) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const navigation = performance.getEntriesByType('navigation')[0];
                    
                    this.metrics.pageLoad = {
                        domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
                        loadComplete: navigation.loadEventEnd - navigation.loadEventStart,
                        totalTime: navigation.loadEventEnd - navigation.fetchStart,
                        dnsLookup: navigation.domainLookupEnd - navigation.domainLookupStart,
                        tcpConnection: navigation.connectEnd - navigation.connectStart,
                        serverResponse: navigation.responseEnd - navigation.requestStart,
                        domProcessing: navigation.domComplete - navigation.domLoading,
                        resourceLoading: navigation.loadEventStart - navigation.domContentLoadedEventEnd
                    };
                    
                    this.logPageLoadMetrics();
                }, 100);
            });
        }
    }

    /**
     * Measure resource loading performance
     */
    measureResourceLoading() {
        if ('performance' in window) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const resources = performance.getEntriesByType('resource');
                    
                    const resourceMetrics = {
                        total: resources.length,
                        css: 0,
                        js: 0,
                        images: 0,
                        fonts: 0,
                        other: 0,
                        totalSize: 0,
                        slowest: null,
                        fastest: null
                    };
                    
                    let slowestTime = 0;
                    let fastestTime = Infinity;
                    
                    resources.forEach(resource => {
                        const duration = resource.responseEnd - resource.requestStart;
                        const size = resource.transferSize || 0;
                        
                        resourceMetrics.totalSize += size;
                        
                        if (duration > slowestTime) {
                            slowestTime = duration;
                            resourceMetrics.slowest = {
                                name: resource.name,
                                duration: duration,
                                size: size
                            };
                        }
                        
                        if (duration < fastestTime) {
                            fastestTime = duration;
                            resourceMetrics.fastest = {
                                name: resource.name,
                                duration: duration,
                                size: size
                            };
                        }
                        
                        // Categorize resources
                        if (resource.name.includes('.css')) {
                            resourceMetrics.css++;
                        } else if (resource.name.includes('.js')) {
                            resourceMetrics.js++;
                        } else if (resource.name.match(/\.(jpg|jpeg|png|gif|webp|svg)$/i)) {
                            resourceMetrics.images++;
                        } else if (resource.name.match(/\.(woff|woff2|ttf|eot)$/i)) {
                            resourceMetrics.fonts++;
                        } else {
                            resourceMetrics.other++;
                        }
                    });
                    
                    this.metrics.resources = resourceMetrics;
                    this.logResourceMetrics();
                }, 500);
            });
        }
    }

    /**
     * Measure user interaction performance
     */
    measureUserInteractions() {
        const interactions = {
            clicks: 0,
            scrolls: 0,
            keystrokes: 0,
            formSubmissions: 0
        };
        
        // Track clicks
        document.addEventListener('click', () => {
            interactions.clicks++;
        });
        
        // Track scrolls
        let scrollTimeout;
        document.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                interactions.scrolls++;
            }, 100);
        });
        
        // Track keystrokes
        document.addEventListener('keydown', () => {
            interactions.keystrokes++;
        });
        
        // Track form submissions
        document.addEventListener('submit', () => {
            interactions.formSubmissions++;
        });
        
        this.metrics.interactions = interactions;
    }

    /**
     * Setup Performance Observer for advanced metrics
     */
    setupPerformanceObserver() {
        if ('PerformanceObserver' in window) {
            // Observe paint metrics
            try {
                const paintObserver = new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    entries.forEach(entry => {
                        if (entry.name === 'first-contentful-paint') {
                            this.metrics.firstContentfulPaint = entry.startTime;
                        }
                        if (entry.name === 'largest-contentful-paint') {
                            this.metrics.largestContentfulPaint = entry.startTime;
                        }
                    });
                });
                
                paintObserver.observe({ entryTypes: ['paint', 'largest-contentful-paint'] });
                this.observers.push(paintObserver);
            } catch (e) {
                console.warn('Paint observer not supported');
            }
            
            // Observe layout shifts
            try {
                const layoutObserver = new PerformanceObserver((list) => {
                    let cumulativeLayoutShift = 0;
                    
                    list.getEntries().forEach(entry => {
                        if (!entry.hadRecentInput) {
                            cumulativeLayoutShift += entry.value;
                        }
                    });
                    
                    this.metrics.cumulativeLayoutShift = cumulativeLayoutShift;
                });
                
                layoutObserver.observe({ entryTypes: ['layout-shift'] });
                this.observers.push(layoutObserver);
            } catch (e) {
                console.warn('Layout shift observer not supported');
            }
        }
    }

    /**
     * Monitor memory usage
     */
    monitorMemoryUsage() {
        if ('memory' in performance) {
            setInterval(() => {
                this.metrics.memory = {
                    used: performance.memory.usedJSHeapSize,
                    total: performance.memory.totalJSHeapSize,
                    limit: performance.memory.jsHeapSizeLimit,
                    percentage: (performance.memory.usedJSHeapSize / performance.memory.jsHeapSizeLimit * 100).toFixed(2)
                };
            }, 5000);
        }
    }

    /**
     * Get Core Web Vitals
     */
    getCoreWebVitals() {
        return {
            LCP: this.metrics.largestContentfulPaint || 'N/A', // Should be < 2.5s
            FID: this.metrics.firstInputDelay || 'N/A', // Should be < 100ms
            CLS: this.metrics.cumulativeLayoutShift || 'N/A', // Should be < 0.1
            FCP: this.metrics.firstContentfulPaint || 'N/A' // Should be < 1.8s
        };
    }

    /**
     * Log page load metrics
     */
    logPageLoadMetrics() {
        if (!this.metrics.pageLoad) return;
        
        const metrics = this.metrics.pageLoad;
        
        console.group('⚡ Page Load Performance');
        console.log('🏁 Total load time:', `${metrics.totalTime.toFixed(2)}ms`);
        console.log('📄 DOM Content Loaded:', `${metrics.domContentLoaded.toFixed(2)}ms`);
        console.log('🔄 DOM Processing:', `${metrics.domProcessing.toFixed(2)}ms`);
        console.log('🌐 Server Response:', `${metrics.serverResponse.toFixed(2)}ms`);
        console.log('📦 Resource Loading:', `${metrics.resourceLoading.toFixed(2)}ms`);
        console.groupEnd();
    }

    /**
     * Log resource metrics
     */
    logResourceMetrics() {
        if (!this.metrics.resources) return;
        
        const metrics = this.metrics.resources;
        
        console.group('📦 Resource Loading Performance');
        console.log('📊 Total resources:', metrics.total);
        console.log('🎨 CSS files:', metrics.css);
        console.log('⚙️ JavaScript files:', metrics.js);
        console.log('🖼️ Images:', metrics.images);
        console.log('🔤 Fonts:', metrics.fonts);
        console.log('📁 Total size:', `${(metrics.totalSize / 1024).toFixed(2)} KB`);
        
        if (metrics.slowest) {
            console.log('🐌 Slowest resource:', metrics.slowest.name, `(${metrics.slowest.duration.toFixed(2)}ms)`);
        }
        
        console.groupEnd();
    }

    /**
     * Generate performance report
     */
    generateReport() {
        const report = {
            timestamp: new Date().toISOString(),
            pageLoad: this.metrics.pageLoad,
            resources: this.metrics.resources,
            coreWebVitals: this.getCoreWebVitals(),
            memory: this.metrics.memory,
            interactions: this.metrics.interactions,
            recommendations: this.getRecommendations()
        };
        
        return report;
    }

    /**
     * Get performance recommendations
     */
    getRecommendations() {
        const recommendations = [];
        
        if (this.metrics.pageLoad) {
            if (this.metrics.pageLoad.totalTime > 3000) {
                recommendations.push('⚠️ Page load time is slow (>3s). Consider optimizing resources.');
            }
            
            if (this.metrics.pageLoad.serverResponse > 500) {
                recommendations.push('⚠️ Server response time is slow (>500ms). Optimize backend performance.');
            }
        }
        
        if (this.metrics.resources) {
            if (this.metrics.resources.totalSize > 2048 * 1024) {
                recommendations.push('⚠️ Total resource size is large (>2MB). Consider compression and optimization.');
            }
            
            if (this.metrics.resources.images > 20) {
                recommendations.push('⚠️ Many images loaded. Consider lazy loading and image optimization.');
            }
        }
        
        if (this.metrics.memory && this.metrics.memory.percentage > 80) {
            recommendations.push('⚠️ High memory usage detected. Check for memory leaks.');
        }
        
        return recommendations;
    }

    /**
     * Log complete performance report
     */
    logCompleteReport() {
        const report = this.generateReport();
        
        console.group('📊 Complete Performance Report');
        console.log('⏰ Generated at:', report.timestamp);
        
        // Core Web Vitals
        console.group('🎯 Core Web Vitals');
        Object.entries(report.coreWebVitals).forEach(([key, value]) => {
            console.log(`${key}:`, typeof value === 'number' ? `${value.toFixed(2)}ms` : value);
        });
        console.groupEnd();
        
        // Memory usage
        if (report.memory) {
            console.group('💾 Memory Usage');
            console.log('Used:', `${(report.memory.used / 1024 / 1024).toFixed(2)} MB`);
            console.log('Percentage:', `${report.memory.percentage}%`);
            console.groupEnd();
        }
        
        // Recommendations
        if (report.recommendations.length > 0) {
            console.group('💡 Recommendations');
            report.recommendations.forEach(rec => console.log(rec));
            console.groupEnd();
        }
        
        console.groupEnd();
        
        return report;
    }
}

// Auto-initialize performance monitoring
document.addEventListener('DOMContentLoaded', function() {
    window.performanceMonitor = new PerformanceMonitor();
    
    // Log complete report after page is fully loaded
    window.addEventListener('load', () => {
        setTimeout(() => {
            window.performanceMonitor.logCompleteReport();
        }, 2000);
    });
});

// Export for manual use
window.PerformanceMonitor = PerformanceMonitor;
