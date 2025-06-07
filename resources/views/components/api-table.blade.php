{{-- API Table Component - Tái sử dụng cho nhiều bảng dữ liệu --}}
<div class="api-table-container" id="{{ $id }}-container">
    {{-- Loading Spinner --}}
    <div id="{{ $id }}-loading" class="text-center py-4">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Đang tải...</span>
        </div>
    </div>

    {{-- Error Message --}}
    <div id="{{ $id }}-error" class="alert alert-danger d-none" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <span class="error-message">Có lỗi xảy ra khi tải dữ liệu</span>
    </div>

    {{-- Table --}}
    <div id="{{ $id }}-table" class="d-none">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        @foreach ($columns as $column)
                            <th>{{ $column }}</th>
                        @endforeach
                        @if(isset($actions) && $actions)
                            <th width="150">Thao tác</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="{{ $id }}-tbody">
                    {{-- Dữ liệu sẽ được render bằng JavaScript --}}
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div id="{{ $id }}-pagination" class="d-flex justify-content-between align-items-center mt-3">
            <div class="pagination-info">
                <span id="{{ $id }}-info" class="text-muted"></span>
            </div>
            <nav>
                <ul id="{{ $id }}-pagination-links" class="pagination pagination-sm mb-0">
                    {{-- Pagination links sẽ được render bằng JavaScript --}}
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const config = {
        id: @json($id),
        url: @json($url),
        fields: @json($fields),
        columns: @json($columns),
        actions: @json($actions ?? false),
        actionButtons: @json($actionButtons ?? []),
        searchable: @json($searchable ?? false),
        perPage: @json($perPage ?? 10)
    };

    class ApiTable {
        constructor(config) {
            this.config = config;
            this.currentPage = 1;
            this.searchTerm = '';
            this.currentFilters = {}; // Khởi tạo filters
            this.init();
        }

        init() {
            this.bindElements();
            this.loadData();
            if (this.config.searchable) {
                this.createSearchBox();
            }
        }

        bindElements() {
            this.container = document.getElementById(`${this.config.id}-container`);
            this.loading = document.getElementById(`${this.config.id}-loading`);
            this.error = document.getElementById(`${this.config.id}-error`);
            this.table = document.getElementById(`${this.config.id}-table`);
            this.tbody = document.getElementById(`${this.config.id}-tbody`);
            this.pagination = document.getElementById(`${this.config.id}-pagination-links`);
            this.info = document.getElementById(`${this.config.id}-info`);
        }

        createSearchBox() {
            const searchHtml = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="${this.config.id}-search" class="form-control" placeholder="Tìm kiếm...">
                            <button class="btn btn-outline-secondary" type="button" id="${this.config.id}-search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            this.container.insertAdjacentHTML('afterbegin', searchHtml);

            const searchInput = document.getElementById(`${this.config.id}-search`);
            const searchBtn = document.getElementById(`${this.config.id}-search-btn`);

            searchBtn.addEventListener('click', () => this.search());
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.search();
            });
        }

        search() {
            const searchInput = document.getElementById(`${this.config.id}-search`);
            this.searchTerm = searchInput.value;
            this.currentPage = 1;
            this.loadData();
        }

        async loadData(page = 1, additionalFilters = null) {
            this.currentPage = page;
            this.showLoading();

            try {
                const params = new URLSearchParams({
                    page: this.currentPage,
                    per_page: this.config.perPage
                });

                if (this.searchTerm) {
                    params.append('search', this.searchTerm);
                }

                // Sử dụng filters hiện tại nếu không có additionalFilters
                const filtersToUse = additionalFilters !== null ? additionalFilters : (this.currentFilters || {});

                // Add filters
                Object.keys(filtersToUse).forEach(key => {
                    if (filtersToUse[key] !== '' && filtersToUse[key] !== null) {
                        params.append(key, filtersToUse[key]);
                    }
                });

                // Xử lý URL đúng cách - kiểm tra xem đã có query params chưa
                const baseUrl = this.config.url;
                const separator = baseUrl.includes('?') ? '&' : '?';
                const url = `${baseUrl}${separator}${params}`;
                console.log('Loading data from:', url);

                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Received data:', data);

                this.renderTable(data);
                this.renderPagination(data);
                this.showTable();

            } catch (error) {
                console.error('Error loading data:', error);
                this.showError(error.message);
            }
        }

        // Method to apply external filters
        applyFilters(filters) {
            this.currentFilters = filters; // Lưu filters hiện tại
            this.currentPage = 1;
            this.loadData(1, filters);
        }

        renderTable(data) {
            console.log('Rendering table with data:', data);
            this.tbody.innerHTML = '';

            // Kiểm tra cấu trúc dữ liệu
            let items = data.data || data;
            if (Array.isArray(data) && !data.data) {
                items = data;
            }

            if (!items || items.length === 0) {
                this.tbody.innerHTML = `
                    <tr>
                        <td colspan="${this.config.columns.length + (this.config.actions ? 1 : 0)}" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Không có dữ liệu</p>
                        </td>
                    </tr>
                `;
                return;
            }

            console.log('Items to render:', items);

            items.forEach((item, index) => {
                console.log(`Rendering item ${index}:`, item);
                const row = document.createElement('tr');

                // Render data columns
                this.config.fields.forEach(field => {
                    const td = document.createElement('td');
                    td.innerHTML = this.formatValue(item[field], field);
                    row.appendChild(td);
                });

                // Render action buttons
                if (this.config.actions) {
                    const actionTd = document.createElement('td');
                    console.log('Rendering action buttons for item:', item);
                    console.log('Action buttons config:', this.config.actionButtons);
                    actionTd.innerHTML = this.renderActionButtons(item);
                    row.appendChild(actionTd);
                }

                this.tbody.appendChild(row);
            });
        }

        formatValue(value, field) {
            if (value === null || value === undefined) return '-';

            // Custom formatting based on field type
            if (field.includes('date') || field.includes('created_at') || field.includes('updated_at')) {
                return new Date(value).toLocaleDateString('vi-VN');
            }

            // Status formatting
            if (field.includes('status')) {
                if (value === 'active') {
                    return '<span class="badge bg-success">Hoạt động</span>';
                } else if (value === 'inactive') {
                    return '<span class="badge bg-secondary">Không hoạt động</span>';
                } else if (value === 1 || value === true) {
                    return '<span class="badge bg-success">Hiển thị</span>';
                } else {
                    return '<span class="badge bg-secondary">Ẩn</span>';
                }
            }

            // Boolean fields
            if (field === 'is_blocked') {
                return value ? '<span class="badge bg-danger">Khóa</span>' : '<span class="badge bg-success">Hoạt động</span>';
            }

            if (field === 'is_default') {
                return value ? '<span class="badge bg-warning">Có</span>' : '<span class="badge bg-secondary">Không</span>';
            }

            if (field === 'require_login') {
                return value ? '<span class="badge bg-warning text-dark">Yêu cầu</span>' : '<span class="badge bg-info">Không yêu cầu</span>';
            }

            // Count fields
            if (field.includes('count') || field.includes('_count')) {
                return `<span class="badge bg-info">${value}</span>`;
            }

            return value;
        }

        renderActionButtons(item) {
            if (!this.config.actionButtons || this.config.actionButtons.length === 0) {
                return `
                    <a href="/admin/${this.config.id}/edit/${item.id}" class="btn btn-sm btn-warning me-1" title="Sửa">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id})" title="Xóa">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            }

            let buttons = this.config.actionButtons.map(button => {
                const className = button.class || 'btn-primary';
                const icon = button.icon || 'fas fa-cog';
                const title = button.title || '';

                if (button.action === 'delete') {
                    return `<button class="btn btn-sm ${className} me-1" onclick="deleteItem(${item.id}, null, 'Bạn có chắc chắn muốn xóa?', () => window.apiTableInstances['${this.config.id}'].loadData())" title="${title}">
                        <i class="${icon}"></i>
                    </button>`;
                } else if (button.action === 'toggle-status') {
                    return `<button class="btn btn-sm ${className} me-1" onclick="toggleStatus(${item.id}, ${item.is_blocked || false}, null, () => window.apiTableInstances['${this.config.id}'].loadData())" title="${title}">
                        <i class="${icon}"></i>
                    </button>`;
                } else if (button.url) {
                    const url = button.url.replace(':id', item.id);
                    return `<a href="${url}" class="btn btn-sm ${className} me-1" title="${title}">
                        <i class="${icon}"></i>
                    </a>`;
                } else {
                    // Fallback nếu không có URL và không có action
                    return `<button class="btn btn-sm ${className} me-1" onclick="alert('No action defined')" title="${title}">
                        <i class="${icon}"></i>
                    </button>`;
                }
            }).join('');

            // Thêm nút delete mặc định nếu không có
            if (!this.config.actionButtons.some(btn => btn.action === 'delete')) {
                buttons += `<button class="btn btn-sm btn-danger" onclick="deleteItem(${item.id}, null, 'Bạn có chắc chắn muốn xóa?', () => window.apiTableInstances['${this.config.id}'].loadData())" title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>`;
            }

            return buttons;
        }

        renderPagination(data) {
            this.pagination.innerHTML = '';

            // Update info
            const from = data.from || 0;
            const to = data.to || 0;
            const total = data.total || 0;
            this.info.textContent = `Hiển thị ${from}-${to} trong tổng số ${total} bản ghi`;

            if (data.last_page <= 1) return;

            // Previous button
            if (data.prev_page_url) {
                this.pagination.appendChild(this.createPaginationButton('‹', data.current_page - 1, false));
            }

            // Page numbers
            const startPage = Math.max(1, data.current_page - 2);
            const endPage = Math.min(data.last_page, data.current_page + 2);

            for (let i = startPage; i <= endPage; i++) {
                this.pagination.appendChild(this.createPaginationButton(i, i, i === data.current_page));
            }

            // Next button
            if (data.next_page_url) {
                this.pagination.appendChild(this.createPaginationButton('›', data.current_page + 1, false));
            }
        }

        createPaginationButton(text, page, isActive) {
            const li = document.createElement('li');
            li.className = `page-item ${isActive ? 'active' : ''}`;

            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = text;
            a.addEventListener('click', (e) => {
                e.preventDefault();
                if (!isActive) this.loadData(page);
            });

            li.appendChild(a);
            return li;
        }

        showLoading() {
            this.loading.classList.remove('d-none');
            this.table.classList.add('d-none');
            this.error.classList.add('d-none');
        }

        showTable() {
            this.loading.classList.add('d-none');
            this.table.classList.remove('d-none');
            this.error.classList.add('d-none');
        }

        showError(message) {
            this.loading.classList.add('d-none');
            this.table.classList.add('d-none');
            this.error.classList.remove('d-none');
            this.error.querySelector('.error-message').textContent = message;
        }
    }

    // Initialize table and store instance globally
    const tableInstance = new ApiTable(config);

    // Store instance for external access
    if (!window.apiTableInstances) {
        window.apiTableInstances = {};
    }
    window.apiTableInstances[config.id] = tableInstance;
});
</script>
