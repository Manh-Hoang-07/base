/* Combined JS for Admin Panel - Select2 + Toastr + Essential Functions */

// Toastr Configuration
window.toastr = {
    options: {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    },
    
    success: function(message, title) {
        this.show(message, title, 'success');
    },
    
    error: function(message, title) {
        this.show(message, title, 'error');
    },
    
    info: function(message, title) {
        this.show(message, title, 'info');
    },
    
    warning: function(message, title) {
        this.show(message, title, 'warning');
    },
    
    show: function(message, title, type) {
        const container = this.getContainer();
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `${title ? '<div class="toast-title">' + title + '</div>' : ''}<div class="toast-message">${message}</div>`;
        
        container.appendChild(toast);
        
        // Show animation
        setTimeout(() => {
            toast.style.display = 'block';
            toast.style.opacity = '1';
        }, 10);
        
        // Auto hide
        setTimeout(() => {
            this.hide(toast);
        }, parseInt(this.options.timeOut));
        
        return toast;
    },
    
    hide: function(toast) {
        toast.style.opacity = '0';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    },
    
    getContainer: function() {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container toast-top-right';
            document.body.appendChild(container);
        }
        return container;
    }
};

// Simplified Select2 Implementation
(function($) {
    $.fn.select2 = function(options) {
        return this.each(function() {
            const $select = $(this);
            const settings = $.extend({
                placeholder: 'Chọn...',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 10
            }, options);
            
            // Skip if already initialized
            if ($select.data('select2-initialized')) {
                return;
            }
            
            $select.data('select2-initialized', true);
            
            // Create Select2 container
            const $container = $('<div class="select2-container"></div>');
            const $selection = $('<div class="select2-selection"></div>');
            
            if ($select.prop('multiple')) {
                $selection.addClass('select2-selection--multiple');
                initMultipleSelect($select, $container, $selection, settings);
            } else {
                $selection.addClass('select2-selection--single');
                initSingleSelect($select, $container, $selection, settings);
            }
            
            $container.append($selection);
            $select.hide().after($container);
            
            // Handle AJAX if URL provided
            const url = $select.data('url');
            if (url) {
                setupAjax($select, $container, settings, url);
            }
        });
    };
    
    function initSingleSelect($select, $container, $selection, settings) {
        const $rendered = $('<span class="select2-selection__rendered"></span>');
        const $arrow = $('<span class="select2-selection__arrow"><b></b></span>');
        
        $selection.append($rendered, $arrow);
        
        // Update display
        function updateDisplay() {
            const selected = $select.find('option:selected');
            $rendered.text(selected.text() || settings.placeholder);
        }
        
        updateDisplay();
        
        // Click handler
        $selection.on('click', function() {
            toggleDropdown($select, $container, settings);
        });
        
        // Update on change
        $select.on('change', updateDisplay);
    }
    
    function initMultipleSelect($select, $container, $selection, settings) {
        const $rendered = $('<ul class="select2-selection__rendered"></ul>');
        $selection.append($rendered);
        
        function updateDisplay() {
            $rendered.empty();
            
            $select.find('option:selected').each(function() {
                const $choice = $('<li class="select2-selection__choice"></li>');
                const $remove = $('<span class="select2-selection__choice__remove">×</span>');
                const $text = $('<span></span>').text($(this).text());
                
                $choice.append($remove, $text);
                $rendered.append($choice);
                
                $remove.on('click', (e) => {
                    e.stopPropagation();
                    $(this).prop('selected', false);
                    $select.trigger('change');
                    updateDisplay();
                });
            });
            
            // Add search input
            const $search = $('<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" placeholder="' + settings.placeholder + '"></li>');
            $rendered.append($search);
        }
        
        updateDisplay();
        
        $selection.on('click', function() {
            toggleDropdown($select, $container, settings);
        });
        
        $select.on('change', updateDisplay);
    }
    
    function toggleDropdown($select, $container, settings) {
        let $dropdown = $container.find('.select2-dropdown');
        
        if ($dropdown.length) {
            $dropdown.remove();
            return;
        }
        
        $dropdown = $('<div class="select2-dropdown"></div>');
        const $results = $('<div class="select2-results"><ul class="select2-results__options"></ul></div>');
        
        // Add search if needed
        if (settings.minimumResultsForSearch >= 0) {
            const $search = $('<div class="select2-search"><input class="select2-search__field" type="search" placeholder="Tìm kiếm..."></div>');
            $dropdown.append($search);
            
            $search.find('input').on('input', function() {
                const term = $(this).val().toLowerCase();
                filterOptions($results, term);
            });
        }
        
        $dropdown.append($results);
        $container.append($dropdown);
        
        populateOptions($select, $results, settings);
        
        // Position dropdown
        $dropdown.css({
            position: 'absolute',
            top: $container.height(),
            left: 0,
            right: 0,
            zIndex: 1051
        });
        
        // Close on outside click
        $(document).on('click.select2', function(e) {
            if (!$container.is(e.target) && !$container.has(e.target).length) {
                $dropdown.remove();
                $(document).off('click.select2');
            }
        });
    }
    
    function populateOptions($select, $results, settings) {
        const $list = $results.find('.select2-results__options');
        $list.empty();
        
        $select.find('option').each(function() {
            const $option = $(this);
            const $item = $('<li class="select2-results__option"></li>');
            $item.text($option.text());
            $item.attr('data-value', $option.val());
            
            if ($option.is(':selected')) {
                $item.attr('aria-selected', 'true');
            }
            
            $item.on('click', function() {
                const value = $(this).attr('data-value');
                
                if ($select.prop('multiple')) {
                    $select.find(`option[value="${value}"]`).prop('selected', true);
                } else {
                    $select.val(value);
                }
                
                $select.trigger('change');
                $results.closest('.select2-dropdown').remove();
            });
            
            $list.append($item);
        });
    }
    
    function filterOptions($results, term) {
        $results.find('.select2-results__option').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(term));
        });
    }
    
    function setupAjax($select, $container, settings, url) {
        // AJAX implementation for autocomplete
        let timeout;
        
        $(document).on('input', '.select2-search__field', function() {
            const term = $(this).val();
            
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                loadAjaxData(url, term, $select, $container);
            }, 300);
        });
        
        // Load initial data
        loadAjaxData(url, '', $select, $container);
    }
    
    function loadAjaxData(url, term, $select, $container) {
        console.log('Loading AJAX data:', { url, term });
        
        $.ajax({
            url: url,
            data: { search: term, limit: 20 },
            dataType: 'json',
            success: function(response) {
                console.log('AJAX response:', response);
                
                let data = response;
                if (response.success && response.data) {
                    data = response.data;
                } else if (!Array.isArray(response)) {
                    console.warn('Unexpected response format:', response);
                    return;
                }
                
                // Update options
                const selectedValues = $select.val() || [];
                $select.empty();
                
                data.forEach(item => {
                    const option = new Option(item.name, item.id, false, selectedValues.includes(item.id));
                    $select.append(option);
                });
                
                $select.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            }
        });
    }
    
})(jQuery);
