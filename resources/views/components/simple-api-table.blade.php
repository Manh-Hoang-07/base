{{-- Simple API Table for debugging --}}
<div id="{{ $id }}-container">
    <div id="{{ $id }}-loading">Loading...</div>
    <div id="{{ $id }}-error" style="display: none; color: red;"></div>
    <table id="{{ $id }}-table" style="display: none;" class="table table-striped">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="{{ $id }}-tbody">
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const config = {
        id: @json($id),
        url: @json($url),
        fields: @json($fields),
        columns: @json($columns)
    };

    console.log('Simple API Table Config:', config);

    const loading = document.getElementById(config.id + '-loading');
    const error = document.getElementById(config.id + '-error');
    const table = document.getElementById(config.id + '-table');
    const tbody = document.getElementById(config.id + '-tbody');

    // Function to load data with filters
    function loadData(filters = {}) {
        loading.style.display = 'block';
        table.style.display = 'none';
        error.style.display = 'none';

        // Build URL with filters
        const params = new URLSearchParams(filters);
        const url = config.url + (config.url.includes('?') ? '&' : '?') + params.toString();

        console.log('Loading data from:', url);

        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);

                loading.style.display = 'none';
                table.style.display = 'table';

                // Clear tbody
                tbody.innerHTML = '';

                // Handle different data structures
                let items = data.data || data;
                if (!Array.isArray(items)) {
                    console.error('Data is not an array:', items);
                    error.textContent = 'Invalid data format';
                    error.style.display = 'block';
                    return;
                }

                console.log('Items to render:', items);

                items.forEach(item => {
                    console.log('Rendering item:', item);
                    const row = document.createElement('tr');

                    config.fields.forEach(field => {
                        const td = document.createElement('td');
                        let value = item[field];

                        // Simple formatting
                        if (field === 'is_blocked') {
                            value = value ? 'Khóa' : 'Hoạt động';
                        } else if (field === 'created_at') {
                            value = new Date(value).toLocaleDateString('vi-VN');
                        }

                        td.textContent = value || '-';
                        row.appendChild(td);
                    });

                    // Actions
                    const actionTd = document.createElement('td');
                    actionTd.innerHTML = `
                        <a href="/admin/users/assign-roles/${item.id}" class="btn btn-sm btn-info me-1" title="Gán vai trò">
                            <i class="fas fa-user-tag"></i>
                        </a>
                        <button onclick="deleteItem(${item.id})" class="btn btn-sm btn-danger" title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                    row.appendChild(actionTd);

                    tbody.appendChild(row);
                });
            })
            .catch(err => {
                console.error('Error:', err);
                loading.style.display = 'none';
                error.textContent = 'Error: ' + err.message;
                error.style.display = 'block';
            });
    }

    // Initial load
    loadData();

    // Filter functionality
    const filterBtn = document.getElementById('filter-btn');
    const resetBtn = document.getElementById('reset-btn');

    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const filters = {};

            const emailInput = document.getElementById('filter-email');
            const statusSelect = document.getElementById('filter-status');

            if (emailInput && emailInput.value) {
                filters.email = emailInput.value;
            }

            if (statusSelect && statusSelect.value !== '') {
                filters.is_blocked = statusSelect.value;
            }

            console.log('Applying filters:', filters);
            loadData(filters);
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            // Clear form
            const emailInput = document.getElementById('filter-email');
            const statusSelect = document.getElementById('filter-status');

            if (emailInput) emailInput.value = '';
            if (statusSelect) statusSelect.value = '';

            // Reload without filters
            loadData();
        });
    }

    // Expose loadData function globally for external use
    window.loadData = loadData;
});
</script>
