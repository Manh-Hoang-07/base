<template>
  <div v-if="totalPages > 1" class="pagination-wrapper">
    <!-- Info text -->
    <div v-if="showInfo" class="pagination-info">
      <span class="text-muted">
        Hiển thị {{ from }} - {{ to }} trong tổng số {{ total }} bản ghi
      </span>
    </div>

    <!-- Pagination controls -->
    <nav :aria-label="ariaLabel" class="pagination-nav">
      <ul class="pagination" :class="paginationClass">
        <!-- First page button -->
        <li v-if="showFirstLast" class="page-item" :class="{ disabled: currentPage === 1 }">
          <button 
            class="page-link" 
            @click="changePage(1)"
            :disabled="currentPage === 1"
            :title="firstPageText"
          >
            <i class="fas fa-angle-double-left"></i>
          </button>
        </li>

        <!-- Previous page button -->
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button 
            class="page-link" 
            @click="changePage(currentPage - 1)"
            :disabled="currentPage === 1"
            :title="previousPageText"
          >
            <i class="fas fa-chevron-left"></i>
          </button>
        </li>

        <!-- Page numbers -->
        <li
          v-for="page in visiblePages"
          :key="page"
          class="page-item"
          :class="{ active: page === currentPage }"
        >
          <button 
            class="page-link" 
            @click="changePage(page)"
            :aria-current="page === currentPage ? 'page' : null"
          >
            {{ page }}
          </button>
        </li>

        <!-- Next page button -->
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button 
            class="page-link" 
            @click="changePage(currentPage + 1)"
            :disabled="currentPage === totalPages"
            :title="nextPageText"
          >
            <i class="fas fa-chevron-right"></i>
          </button>
        </li>

        <!-- Last page button -->
        <li v-if="showFirstLast" class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button 
            class="page-link" 
            @click="changePage(totalPages)"
            :disabled="currentPage === totalPages"
            :title="lastPageText"
          >
            <i class="fas fa-angle-double-right"></i>
          </button>
        </li>
      </ul>
    </nav>

    <!-- Per page selector -->
    <div v-if="showPerPageSelector" class="per-page-selector">
      <label class="form-label">Hiển thị:</label>
      <select 
        v-model="selectedPerPage" 
        @change="changePerPage"
        class="form-select form-select-sm"
      >
        <option v-for="option in perPageOptions" :key="option" :value="option">
          {{ option }}
        </option>
      </select>
      <span class="text-muted">/ trang</span>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch } from 'vue'

export default {
  name: 'Pagination',
  props: {
    // Current page (1-based)
    currentPage: {
      type: Number,
      default: 1
    },
    // Total number of pages
    totalPages: {
      type: Number,
      required: true
    },
    // Total number of items
    total: {
      type: Number,
      default: 0
    },
    // Items from (for info display)
    from: {
      type: Number,
      default: 0
    },
    // Items to (for info display)
    to: {
      type: Number,
      default: 0
    },
    // Number of visible page links
    visibleRange: {
      type: Number,
      default: 5
    },
    // Show first/last page buttons
    showFirstLast: {
      type: Boolean,
      default: false
    },
    // Show info text
    showInfo: {
      type: Boolean,
      default: true
    },
    // Show per page selector
    showPerPageSelector: {
      type: Boolean,
      default: false
    },
    // Per page options
    perPageOptions: {
      type: Array,
      default: () => [10, 25, 50, 100]
    },
    // Current per page value
    perPage: {
      type: Number,
      default: 10
    },
    // Pagination size
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    // Aria label
    ariaLabel: {
      type: String,
      default: 'Pagination Navigation'
    },
    // Text labels
    firstPageText: {
      type: String,
      default: 'Trang đầu'
    },
    lastPageText: {
      type: String,
      default: 'Trang cuối'
    },
    previousPageText: {
      type: String,
      default: 'Trang trước'
    },
    nextPageText: {
      type: String,
      default: 'Trang sau'
    }
  },
  emits: ['page-change', 'per-page-change'],
  setup(props, { emit }) {
    const selectedPerPage = ref(props.perPage)

    const paginationClass = computed(() => {
      const classes = []
      if (props.size === 'sm') classes.push('pagination-sm')
      if (props.size === 'lg') classes.push('pagination-lg')
      return classes
    })

    const visiblePages = computed(() => {
      const current = props.currentPage
      const total = props.totalPages
      const range = props.visibleRange
      const pages = []

      if (total <= range) {
        // Show all pages if total is less than or equal to range
        for (let i = 1; i <= total; i++) {
          pages.push(i)
        }
      } else {
        // Calculate start and end based on current page
        let start = Math.max(1, current - Math.floor(range / 2))
        let end = Math.min(total, start + range - 1)

        // Adjust start if end is at the boundary
        if (end === total) {
          start = Math.max(1, end - range + 1)
        }

        for (let i = start; i <= end; i++) {
          pages.push(i)
        }
      }

      return pages
    })

    const changePage = (page) => {
      if (page >= 1 && page <= props.totalPages && page !== props.currentPage) {
        emit('page-change', page)
      }
    }

    const changePerPage = () => {
      emit('per-page-change', selectedPerPage.value)
    }

    // Watch for external per page changes
    watch(() => props.perPage, (newValue) => {
      selectedPerPage.value = newValue
    })

    return {
      selectedPerPage,
      paginationClass,
      visiblePages,
      changePage,
      changePerPage
    }
  }
}
</script>

<style scoped>
.pagination-wrapper {
  display: flex;
  justify-content: between;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.pagination-info {
  flex: 1;
  min-width: 200px;
}

.pagination-nav {
  flex: 0 0 auto;
}

.per-page-selector {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex: 0 0 auto;
}

.per-page-selector .form-label {
  margin: 0;
  font-size: 0.875rem;
  white-space: nowrap;
}

.per-page-selector .form-select {
  width: auto;
  min-width: 70px;
}

.pagination {
  margin: 0;
}

.page-link {
  border-radius: 0.25rem;
  margin: 0 2px;
  transition: all 0.15s ease-in-out;
}

.page-link:hover {
  background-color: #e9ecef;
  border-color: #dee2e6;
}

.page-item.active .page-link {
  background-color: #0d6efd;
  border-color: #0d6efd;
  color: #fff;
}

.page-item.disabled .page-link {
  color: #6c757d;
  background-color: #fff;
  border-color: #dee2e6;
  cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
  .pagination-wrapper {
    flex-direction: column;
    align-items: stretch;
    gap: 0.75rem;
  }

  .pagination-info {
    text-align: center;
    order: 3;
  }

  .pagination-nav {
    order: 1;
    display: flex;
    justify-content: center;
  }

  .per-page-selector {
    order: 2;
    justify-content: center;
  }

  .pagination-sm .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
  }
}

@media (max-width: 576px) {
  .page-link {
    padding: 0.375rem 0.5rem;
    font-size: 0.8rem;
  }

  .pagination-sm .page-link {
    padding: 0.2rem 0.4rem;
    font-size: 0.7rem;
  }
}

/* Animation */
.page-link {
  position: relative;
  overflow: hidden;
}

.page-link::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

.page-link:hover::before {
  left: 100%;
}
</style>
