<template>
  <div class="data-table">
    <!-- Filters -->
    <div v-if="showFilters" class="card mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label">Tìm kiếm</label>
            <input
              v-model="localFilters.search"
              type="text"
              class="form-control"
              :placeholder="searchPlaceholder"
              @keyup.enter="applyFilters"
            >
          </div>
          <div v-if="statusOptions" class="col-md-3">
            <label class="form-label">Trạng thái</label>
            <select v-model="localFilters.status" class="form-select">
              <option value="">Tất cả</option>
              <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Số bản ghi</label>
            <select v-model="localFilters.per_page" class="form-select">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button @click="applyFilters" class="btn btn-outline-primary me-2">
              <i class="fas fa-search"></i>
            </button>
            <button @click="resetFilters" class="btn btn-outline-secondary">
              <i class="fas fa-undo"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="card">
      <div class="card-body">
        <!-- Loading -->
        <div v-if="loading" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>

        <!-- Table -->
        <div v-else class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th v-if="selectable">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    :checked="allSelected"
                    @change="toggleSelectAll"
                  >
                </th>
                <th
                  v-for="column in columns"
                  :key="column.key"
                  :class="{ 'sortable': column.sortable }"
                  @click="column.sortable ? sort(column.key) : null"
                >
                  {{ column.label }}
                  <i
                    v-if="column.sortable && sortBy === column.key"
                    :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'"
                    class="ms-1"
                  ></i>
                  <i v-else-if="column.sortable" class="fas fa-sort ms-1 text-muted"></i>
                </th>
                <th v-if="actions.length > 0">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in (data?.data || [])" :key="item.id">
                <td v-if="selectable">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    :value="item.id"
                    v-model="selectedItems"
                  >
                </td>
                <td v-for="column in columns" :key="column.key">
                  <slot :name="`column-${column.key}`" :item="item" :value="getNestedValue(item, column.key)">
                    {{ formatValue(getNestedValue(item, column.key), column.type) }}
                  </slot>
                </td>
                <td v-if="actions.length > 0">
                  <div class="btn-group btn-group-sm">
                    <button
                      v-for="action in actions"
                      :key="action.name"
                      @click="$emit('action', action.name, item)"
                      :class="action.class || 'btn btn-outline-primary'"
                      :title="action.title"
                    >
                      <i :class="action.icon"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Empty state -->
          <div v-if="data.data?.length === 0" class="text-center py-4">
            <slot name="empty">
              <i :class="emptyIcon" class="fa-3x text-muted mb-3"></i>
              <h5 class="text-muted">{{ emptyMessage }}</h5>
            </slot>
          </div>
        </div>

        <!-- Bulk Actions -->
        <div v-if="selectable && selectedItems.length > 0" class="d-flex justify-content-between align-items-center mt-3">
          <div>
            <span class="text-muted">Đã chọn {{ selectedItems.length }} mục</span>
            <button
              v-for="bulkAction in bulkActions"
              :key="bulkAction.name"
              @click="$emit('bulk-action', bulkAction.name, selectedItems)"
              :class="bulkAction.class || 'btn btn-outline-danger btn-sm ms-2'"
            >
              <i :class="bulkAction.icon" class="me-1"></i>
              {{ bulkAction.label }}
            </button>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="data.last_page > 1" class="d-flex justify-content-between align-items-center mt-4">
          <div class="text-muted">
            Hiển thị {{ data.from }} - {{ data.to }} trong tổng số {{ data.total }} bản ghi
          </div>

          <nav aria-label="Table pagination">
            <ul class="pagination pagination-sm mb-0">
              <li class="page-item" :class="{ disabled: data.current_page === 1 }">
                <button class="page-link" @click="changePage(data.current_page - 1)">
                  <i class="fas fa-chevron-left"></i>
                </button>
              </li>

              <li
                v-for="page in visiblePages"
                :key="page"
                class="page-item"
                :class="{ active: page === data.current_page }"
              >
                <button class="page-link" @click="changePage(page)">
                  {{ page }}
                </button>
              </li>

              <li class="page-item" :class="{ disabled: data.current_page === data.last_page }">
                <button class="page-link" @click="changePage(data.current_page + 1)">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, computed, watch } from 'vue'

export default {
  name: 'DataTable',
  props: {
    data: {
      type: Object,
      default: () => ({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
    },
    columns: {
      type: Array,
      required: true
    },
    actions: {
      type: Array,
      default: () => []
    },
    bulkActions: {
      type: Array,
      default: () => []
    },
    loading: {
      type: Boolean,
      default: false
    },
    showFilters: {
      type: Boolean,
      default: true
    },
    selectable: {
      type: Boolean,
      default: false
    },
    searchPlaceholder: {
      type: String,
      default: 'Tìm kiếm...'
    },
    statusOptions: {
      type: Array,
      default: null
    },
    emptyMessage: {
      type: String,
      default: 'Không có dữ liệu'
    },
    emptyIcon: {
      type: String,
      default: 'fas fa-inbox'
    }
  },
  emits: ['filter', 'sort', 'page-change', 'action', 'bulk-action'],
  setup(props, { emit }) {
    const localFilters = reactive({
      search: '',
      status: '',
      per_page: 10
    })

    const selectedItems = ref([])
    const sortBy = ref('')
    const sortOrder = ref('asc')

    const allSelected = computed(() => {
      return props.data.data?.length > 0 && selectedItems.value.length === props.data.data.length
    })

    const visiblePages = computed(() => {
      const current = props.data.current_page
      const last = props.data.last_page
      const pages = []

      const start = Math.max(1, current - 2)
      const end = Math.min(last, current + 2)

      for (let i = start; i <= end; i++) {
        pages.push(i)
      }

      return pages
    })

    const applyFilters = () => {
      emit('filter', { ...localFilters })
    }

    const resetFilters = () => {
      localFilters.search = ''
      localFilters.status = ''
      localFilters.per_page = 10
      selectedItems.value = []
      applyFilters()
    }

    const sort = (column) => {
      if (sortBy.value === column) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
      } else {
        sortBy.value = column
        sortOrder.value = 'asc'
      }
      emit('sort', { column: sortBy.value, order: sortOrder.value })
    }

    const changePage = (page) => {
      if (page >= 1 && page <= props.data.last_page) {
        emit('page-change', page)
      }
    }

    const toggleSelectAll = () => {
      if (allSelected.value) {
        selectedItems.value = []
      } else {
        selectedItems.value = props.data.data.map(item => item.id)
      }
    }

    const getNestedValue = (obj, path) => {
      return path.split('.').reduce((current, key) => current?.[key], obj)
    }

    const formatValue = (value, type) => {
      if (value === null || value === undefined) return '-'

      switch (type) {
        case 'date':
          return new Intl.DateTimeFormat('vi-VN').format(new Date(value))
        case 'datetime':
          return new Intl.DateTimeFormat('vi-VN', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
          }).format(new Date(value))
        case 'currency':
          return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
          }).format(value)
        case 'number':
          return new Intl.NumberFormat('vi-VN').format(value)
        default:
          return value
      }
    }

    // Watch per_page changes
    watch(() => localFilters.per_page, () => {
      applyFilters()
    })



    return {
      localFilters,
      selectedItems,
      sortBy,
      sortOrder,
      allSelected,
      visiblePages,
      applyFilters,
      resetFilters,
      sort,
      changePage,
      toggleSelectAll,
      getNestedValue,
      formatValue
    }
  }
}
</script>

<style scoped>
.sortable {
  cursor: pointer;
  user-select: none;
}

.sortable:hover {
  background-color: #f8f9fa;
}

.pagination .page-link {
  border-radius: 0.25rem;
  margin: 0 2px;
}

.btn-group-sm .btn {
  padding: 0.25rem 0.5rem;
}
</style>
