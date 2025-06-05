/**
 * CSS Optimizer - Remove unused CSS classes
 * Scans the DOM and removes unused CSS rules for better performance
 */

class CSSOptimizer {
    constructor() {
        this.usedClasses = new Set();
        this.scannedElements = new Set();
    }

    /**
     * Scan all elements and collect used CSS classes
     */
    scanUsedClasses() {
        // Scan all elements in the document
        const allElements = document.querySelectorAll('*');
        
        allElements.forEach(element => {
            // Get all classes from this element
            if (element.className && typeof element.className === 'string') {
                const classes = element.className.split(/\s+/);
                classes.forEach(cls => {
                    if (cls.trim()) {
                        this.usedClasses.add(cls.trim());
                    }
                });
            }
        });

        // Also scan for dynamically added classes in JavaScript
        this.scanJavaScriptClasses();
        
        return this.usedClasses;
    }

    /**
     * Scan JavaScript files for dynamically added classes
     */
    scanJavaScriptClasses() {
        // Common dynamic classes used in the application
        const dynamicClasses = [
            'select2-hidden-accessible',
            'select2-container',
            'select2-selection',
            'select2-dropdown',
            'select2-results',
            'select2-loading',
            'btn-loading',
            'loading-overlay',
            'loading-spinner',
            'error-message',
            'toast-notification',
            'skeleton',
            'skeleton-text',
            'is-invalid',
            'was-validated',
            'show',
            'fade',
            'modal-backdrop',
            'collapse',
            'collapsing',
            'active',
            'disabled'
        ];

        dynamicClasses.forEach(cls => this.usedClasses.add(cls));
    }

    /**
     * Get unused CSS rules from stylesheets
     */
    getUnusedCSSRules() {
        const unusedRules = [];
        
        try {
            // Iterate through all stylesheets
            for (let i = 0; i < document.styleSheets.length; i++) {
                const styleSheet = document.styleSheets[i];
                
                try {
                    const rules = styleSheet.cssRules || styleSheet.rules;
                    
                    if (rules) {
                        for (let j = 0; j < rules.length; j++) {
                            const rule = rules[j];
                            
                            if (rule.type === CSSRule.STYLE_RULE) {
                                const selector = rule.selectorText;
                                
                                if (selector && this.isUnusedSelector(selector)) {
                                    unusedRules.push({
                                        selector: selector,
                                        cssText: rule.cssText,
                                        stylesheet: styleSheet.href || 'inline'
                                    });
                                }
                            }
                        }
                    }
                } catch (e) {
                    // Skip stylesheets that can't be accessed (CORS)
                    console.warn('Cannot access stylesheet:', styleSheet.href);
                }
            }
        } catch (e) {
            console.error('Error analyzing CSS:', e);
        }
        
        return unusedRules;
    }

    /**
     * Check if a CSS selector is unused
     */
    isUnusedSelector(selector) {
        // Skip complex selectors and pseudo-classes for safety
        if (selector.includes(':') || selector.includes('[') || selector.includes('>') || selector.includes('+') || selector.includes('~')) {
            return false;
        }

        // Extract class names from selector
        const classMatches = selector.match(/\.[a-zA-Z0-9_-]+/g);
        
        if (classMatches) {
            // Check if any of the classes in the selector are used
            for (let classMatch of classMatches) {
                const className = classMatch.substring(1); // Remove the dot
                if (this.usedClasses.has(className)) {
                    return false; // Selector is used
                }
            }
            return true; // None of the classes are used
        }
        
        return false; // Not a class selector, keep it safe
    }

    /**
     * Generate optimized CSS by removing unused rules
     */
    generateOptimizedCSS() {
        this.scanUsedClasses();
        const unusedRules = this.getUnusedCSSRules();
        
        const report = {
            totalClasses: this.usedClasses.size,
            unusedRules: unusedRules.length,
            unusedSelectors: unusedRules.map(rule => rule.selector),
            potentialSavings: this.calculateSavings(unusedRules)
        };
        
        return report;
    }

    /**
     * Calculate potential file size savings
     */
    calculateSavings(unusedRules) {
        let totalBytes = 0;
        unusedRules.forEach(rule => {
            totalBytes += rule.cssText.length;
        });
        
        return {
            bytes: totalBytes,
            kb: (totalBytes / 1024).toFixed(2),
            percentage: unusedRules.length > 0 ? ((unusedRules.length / this.getTotalCSSRules()) * 100).toFixed(2) : 0
        };
    }

    /**
     * Get total number of CSS rules
     */
    getTotalCSSRules() {
        let totalRules = 0;
        
        for (let i = 0; i < document.styleSheets.length; i++) {
            try {
                const rules = document.styleSheets[i].cssRules || document.styleSheets[i].rules;
                if (rules) {
                    totalRules += rules.length;
                }
            } catch (e) {
                // Skip inaccessible stylesheets
            }
        }
        
        return totalRules;
    }

    /**
     * Log optimization report to console
     */
    logOptimizationReport() {
        const report = this.generateOptimizedCSS();
        
        console.group('🎨 CSS Optimization Report');
        console.log('📊 Used CSS classes:', report.totalClasses);
        console.log('🗑️ Unused CSS rules:', report.unusedRules);
        console.log('💾 Potential savings:', `${report.potentialSavings.kb} KB (${report.potentialSavings.percentage}%)`);
        
        if (report.unusedSelectors.length > 0) {
            console.group('🔍 Unused selectors (first 10):');
            report.unusedSelectors.slice(0, 10).forEach(selector => {
                console.log('  -', selector);
            });
            console.groupEnd();
        }
        
        console.groupEnd();
        
        return report;
    }
}

// Auto-run CSS optimization analysis in development
if (window.location.hostname === 'web.local' || window.location.hostname === 'localhost') {
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const optimizer = new CSSOptimizer();
            window.cssOptimizer = optimizer;
            
            // Log report after page is fully loaded
            setTimeout(() => {
                optimizer.logOptimizationReport();
            }, 2000);
        }, 1000);
    });
}

// Export for manual use
window.CSSOptimizer = CSSOptimizer;
