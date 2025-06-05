/**
 * Image Optimizer - Lazy loading and compression utilities
 * Improves page load performance by optimizing image loading
 */

class ImageOptimizer {
    constructor() {
        this.lazyImages = [];
        this.imageObserver = null;
        this.init();
    }

    /**
     * Initialize image optimization
     */
    init() {
        this.setupLazyLoading();
        this.optimizeExistingImages();
        this.setupImageCompression();
    }

    /**
     * Setup lazy loading for images
     */
    setupLazyLoading() {
        // Check if Intersection Observer is supported
        if ('IntersectionObserver' in window) {
            this.imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        this.loadImage(img);
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px', // Start loading 50px before image comes into view
                threshold: 0.01
            });

            this.observeImages();
        } else {
            // Fallback for older browsers
            this.loadAllImages();
        }
    }

    /**
     * Observe images for lazy loading
     */
    observeImages() {
        const images = document.querySelectorAll('img[data-src], img[loading="lazy"]');
        
        images.forEach(img => {
            // Add loading placeholder
            if (!img.src && img.dataset.src) {
                img.src = this.generatePlaceholder(img.width || 300, img.height || 200);
                img.classList.add('lazy-loading');
            }
            
            this.lazyImages.push(img);
            this.imageObserver.observe(img);
        });
    }

    /**
     * Load individual image
     */
    loadImage(img) {
        return new Promise((resolve, reject) => {
            const imageLoader = new Image();
            
            imageLoader.onload = () => {
                // Image loaded successfully
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                
                img.classList.remove('lazy-loading');
                img.classList.add('lazy-loaded');
                
                // Add fade-in effect
                img.style.opacity = '0';
                img.style.transition = 'opacity 0.3s ease-in-out';
                
                setTimeout(() => {
                    img.style.opacity = '1';
                }, 50);
                
                resolve(img);
            };
            
            imageLoader.onerror = () => {
                // Image failed to load
                img.classList.remove('lazy-loading');
                img.classList.add('lazy-error');
                img.src = this.generateErrorPlaceholder(img.width || 300, img.height || 200);
                reject(new Error('Failed to load image'));
            };
            
            imageLoader.src = img.dataset.src || img.src;
        });
    }

    /**
     * Generate placeholder image
     */
    generatePlaceholder(width, height) {
        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        
        const ctx = canvas.getContext('2d');
        
        // Create gradient background
        const gradient = ctx.createLinearGradient(0, 0, width, height);
        gradient.addColorStop(0, '#f0f0f0');
        gradient.addColorStop(1, '#e0e0e0');
        
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, width, height);
        
        // Add loading text
        ctx.fillStyle = '#999';
        ctx.font = '16px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Đang tải...', width / 2, height / 2);
        
        return canvas.toDataURL('image/png');
    }

    /**
     * Generate error placeholder
     */
    generateErrorPlaceholder(width, height) {
        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        
        const ctx = canvas.getContext('2d');
        
        // Red background
        ctx.fillStyle = '#f8d7da';
        ctx.fillRect(0, 0, width, height);
        
        // Error text
        ctx.fillStyle = '#721c24';
        ctx.font = '16px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Lỗi tải ảnh', width / 2, height / 2);
        
        return canvas.toDataURL('image/png');
    }

    /**
     * Load all images (fallback)
     */
    loadAllImages() {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            }
        });
    }

    /**
     * Optimize existing images
     */
    optimizeExistingImages() {
        const images = document.querySelectorAll('img');
        
        images.forEach(img => {
            // Add loading attribute for modern browsers
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
            }
            
            // Add error handling
            img.addEventListener('error', () => {
                img.src = this.generateErrorPlaceholder(img.width || 300, img.height || 200);
            });
        });
    }

    /**
     * Setup image compression for uploads
     */
    setupImageCompression() {
        const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
        
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                const files = Array.from(e.target.files);
                
                files.forEach(file => {
                    if (file.type.startsWith('image/')) {
                        this.compressImage(file).then(compressedFile => {
                            // Replace original file with compressed version
                            const dt = new DataTransfer();
                            dt.items.add(compressedFile);
                            input.files = dt.files;
                        });
                    }
                });
            });
        });
    }

    /**
     * Compress image file
     */
    compressImage(file, quality = 0.8, maxWidth = 1920, maxHeight = 1080) {
        return new Promise((resolve) => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();
            
            img.onload = () => {
                // Calculate new dimensions
                let { width, height } = img;
                
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
                
                if (height > maxHeight) {
                    width = (width * maxHeight) / height;
                    height = maxHeight;
                }
                
                canvas.width = width;
                canvas.height = height;
                
                // Draw and compress
                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob((blob) => {
                    const compressedFile = new File([blob], file.name, {
                        type: file.type,
                        lastModified: Date.now()
                    });
                    
                    console.log(`Image compressed: ${(file.size / 1024).toFixed(2)}KB → ${(compressedFile.size / 1024).toFixed(2)}KB`);
                    resolve(compressedFile);
                }, file.type, quality);
            };
            
            img.src = URL.createObjectURL(file);
        });
    }

    /**
     * Add new images to lazy loading
     */
    addNewImages() {
        this.observeImages();
    }

    /**
     * Get optimization stats
     */
    getStats() {
        const totalImages = document.querySelectorAll('img').length;
        const lazyImages = this.lazyImages.length;
        const loadedImages = document.querySelectorAll('img.lazy-loaded').length;
        const errorImages = document.querySelectorAll('img.lazy-error').length;
        
        return {
            total: totalImages,
            lazy: lazyImages,
            loaded: loadedImages,
            errors: errorImages,
            pending: lazyImages - loadedImages - errorImages
        };
    }

    /**
     * Log optimization report
     */
    logOptimizationReport() {
        const stats = this.getStats();
        
        console.group('🖼️ Image Optimization Report');
        console.log('📊 Total images:', stats.total);
        console.log('⚡ Lazy loaded images:', stats.lazy);
        console.log('✅ Successfully loaded:', stats.loaded);
        console.log('❌ Failed to load:', stats.errors);
        console.log('⏳ Pending load:', stats.pending);
        console.groupEnd();
        
        return stats;
    }
}

// Auto-initialize image optimization
document.addEventListener('DOMContentLoaded', function() {
    window.imageOptimizer = new ImageOptimizer();
    
    // Log report after images have had time to load
    setTimeout(() => {
        window.imageOptimizer.logOptimizationReport();
    }, 3000);
});

// Export for manual use
window.ImageOptimizer = ImageOptimizer;
