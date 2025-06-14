<template>
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">
          <i class="fas fa-list me-2"></i>Quản lý Series
        </h1>
        <p class="mb-0 text-muted">Quản lý series bài viết</p>
      </div>
      <button @click="showCreateModal" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm Series
      </button>
    </div>

    <!-- Data Table -->
    <DataTable
      :data="series"
      :columns="columns"
      :loading="loading"
      :actions="actions"
      :bulk-actions="bulkActions"
      :status-options="statusOptions"
      @filter="handleFilter"
      @sort="handleSort"
      @page-change="handlePageChange"
      @action="handleAction"
      @bulk-action="handleBulkAction"
    >
      <!-- Custom column: name -->
      <template #column-name="{ item }">
        <div>
          <strong>{{ item.name }}</strong>
          <div v-if="item.slug" class="text-muted small">
            <i class="fas fa-link me-1"></i>{{ item.slug }}
          </div>
        </div>
      </template>

      <!-- Custom column: description -->
      <template #column-description="{ item }">
        <span class="text-muted">{{ truncateText(item.description, 60) }}</span>
      </template>

      <!-- Custom column: status -->
      <template #column-status="{ item }">
        <span
          class="badge"
          :class="(item.status === 'active' || item.status === 1 || item.status === true) ? 'bg-success' : 'bg-warning'"
        >
          {{ (item.status === 'active' || item.status === 1 || item.status === true) ? 'Hoạt động' : 'Không hoạt động' }}
        </span>
      </template>




    </DataTable>

    <!-- Create/Edit Modal -->
    <FormModal
      modal-id="seriesModal"
      :title="editingSeries ? 'Chỉnh sửa Series' : 'Tạo Series mới'"
      :fields="seriesFields"
      :initial-data="editingSeries || {}"
      icon="fas fa-list"
      modal-size="modal-lg"
      @submit="handleSubmit"
    />
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import DataTable from '../../../components/DataTable.vue'
import FormModal from '../../../components/FormModal.vue'
import { useApi } from '../../../composables/useApi'
import { useToast } from '../../../composables/useToast'

export default {
  name: 'AdminSeriesIndex',
  components: {
    DataTable,
    FormModal
  },
  setup() {
    const { fetchList, create, update, remove, toggleStatus, bulkDelete } = useApi()
    const { success, error } = useToast()

    const series = ref({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
    const loading = ref(false)
    const editingSeries = ref(null)
    const availableCategories = ref([])

    // Table configuration
    const columns = [
      { key: 'id', label: 'ID', sortable: true },
      { key: 'name', label: 'Tên Series', sortable: true },
      { key: 'description', label: 'Mô tả' },
      { key: 'status', label: 'Trạng thái' },
      { key: 'created_at', label: 'Ngày tạo', type: 'date', sortable: true }
    ]

    const actions = [
      { name: 'edit', icon: 'fas fa-edit', class: 'btn btn-outline-primary', title: 'Chỉnh sửa' },
      { name: 'toggle-status', icon: 'fas fa-eye', class: 'btn btn-outline-warning', title: 'Thay đổi trạng thái' },
      { name: 'delete', icon: 'fas fa-trash', class: 'btn btn-outline-danger', title: 'Xóa' }
    ]

    const bulkActions = [
      { name: 'bulk-activate', label: 'Kích hoạt', icon: 'fas fa-eye', class: 'btn btn-outline-success btn-sm' },
      { name: 'bulk-deactivate', label: 'Vô hiệu hóa', icon: 'fas fa-eye-slash', class: 'btn btn-outline-warning btn-sm' },
      { name: 'bulk-delete', label: 'Xóa đã chọn', icon: 'fas fa-trash', class: 'btn btn-outline-danger btn-sm' }
    ]

    const statusOptions = [
      { value: '1', label: 'Hoạt động' },
      { value: '0', label: 'Không hoạt động' }
    ]

    // Form fields for modal
    const seriesFields = computed(() => [
      {
        name: 'name',
        label: 'Tên Series',
        type: 'text',
        required: true,
        placeholder: 'Nhập tên series'
      },
      {
        name: 'slug',
        label: 'Slug',
        type: 'text',
        placeholder: 'Nhập slug (tự động tạo nếu để trống)'
      },
      {
        name: 'description',
        label: 'Mô tả',
        type: 'textarea',
        rows: 3,
        placeholder: 'Nhập mô tả series'
      },
      {
        name: 'category_id',
        label: 'Danh mục',
        type: 'select',
        options: availableCategories.value,
        placeholder: 'Chọn danh mục'
      },
      {
        name: 'status',
        label: 'Trạng thái',
        type: 'select',
        options: [
          { value: 0, label: 'Không hoạt động' },
          { value: 1, label: 'Hoạt động' }
        ],
        default: 1
      },
      {
        name: 'image',
        label: 'Hình ảnh',
        type: 'file',
        accept: 'image/*'
      }
    ])

    const fetchSeries = async (filters = {}) => {
      loading.value = true
      try {
        const apiUrl = window.Laravel?.routes?.api?.admin?.series?.list || '/api/v1/admin/series/list'
        const response = await fetchList(apiUrl.replace(window.Laravel.apiUrl, ''), filters)
        if (response && response.data) {
          series.value = response
        } else {
          series.value = {
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            from: 0,
            to: 0
          }
        }
      } catch (err) {
        console.error('Error fetching series:', err)
        series.value = {
          data: [],
          current_page: 1,
          last_page: 1,
          total: 0,
          from: 0,
          to: 0
        }
      } finally {
        loading.value = false
      }
    }

    const fetchCategories = async () => {
      try {
        const apiUrl = window.Laravel?.routes?.api?.admin?.categories?.list || '/api/v1/admin/categories/list'
        const response = await fetchList(apiUrl.replace(window.Laravel.apiUrl, ''))
        if (response && response.data) {
          availableCategories.value = response.data.map(category => ({
            value: category.id,
            label: category.name
          }))
        }
      } catch (err) {
        console.error('Error fetching categories:', err)
      }
    }

    const showCreateModal = async () => {
      editingSeries.value = null
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('seriesModal'))
      modal.show()
    }

    const showEditModal = async (seriesItem) => {
      editingSeries.value = {
        ...seriesItem,
        category_id: seriesItem.category?.id || null
      }
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('seriesModal'))
      modal.show()
    }

    const truncateText = (text, length = 60) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    // Event handlers
    const handleFilter = (filters) => {
      fetchSeries(filters)
    }

    const handleSort = (sort) => {
      fetchSeries({ sort: sort.column, order: sort.order })
    }

    const handlePageChange = (page) => {
      fetchSeries({ page })
    }

    const handleAction = async (action, seriesItem) => {
      switch (action) {
        case 'edit':
          showEditModal(seriesItem)
          break
        case 'toggle-status':
          try {
            await toggleStatus('/v1/admin/series', seriesItem.id, seriesItem.status ? 0 : 1)
            seriesItem.status = seriesItem.status ? 0 : 1
            success('Cập nhật trạng thái thành công')
          } catch (err) {
            console.error('Error toggling status:', err)
            error('Có lỗi xảy ra khi cập nhật trạng thái')
          }
          break
        case 'delete':
          if (confirm(`Bạn có chắc chắn muốn xóa series "${seriesItem.name}"?`)) {
            try {
              await remove('/v1/admin/series/delete', seriesItem.id)
              const index = series.value.data.findIndex(s => s.id === seriesItem.id)
              if (index > -1) {
                series.value.data.splice(index, 1)
              }
              success('Xóa series thành công')
            } catch (err) {
              console.error('Error deleting series:', err)
              error('Có lỗi xảy ra khi xóa series')
            }
          }
          break
      }
    }

    const handleBulkAction = async (action, selectedIds) => {
      switch (action) {
        case 'bulk-activate':
          try {
            await Promise.all(selectedIds.map(id =>
              toggleStatus('/v1/admin/series', id, 1)
            ))
            success(`Đã kích hoạt ${selectedIds.length} series`)
            fetchSeries()
          } catch (err) {
            console.error('Error bulk activating:', err)
            error('Có lỗi xảy ra khi kích hoạt series')
          }
          break
        case 'bulk-deactivate':
          try {
            await Promise.all(selectedIds.map(id =>
              toggleStatus('/v1/admin/series', id, 0)
            ))
            success(`Đã vô hiệu hóa ${selectedIds.length} series`)
            fetchSeries()
          } catch (err) {
            console.error('Error bulk deactivating:', err)
            error('Có lỗi xảy ra khi vô hiệu hóa series')
          }
          break
        case 'bulk-delete':
          if (confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} series đã chọn?`)) {
            try {
              await bulkDelete('/v1/admin/series', selectedIds)
              fetchSeries()
              success(`Đã xóa ${selectedIds.length} series`)
            } catch (err) {
              console.error('Error bulk deleting series:', err)
              error('Có lỗi xảy ra khi xóa series')
            }
          }
          break
      }
    }

    const handleSubmit = async (formData) => {
      try {
        if (editingSeries.value) {
          await update('/v1/admin/series/update', editingSeries.value.id, formData)
          success('Cập nhật series thành công')
        } else {
          await create('/v1/admin/series/create', formData)
          success('Tạo series thành công')
        }
        fetchSeries()
        return true
      } catch (err) {
        console.error('Error submitting form:', err)
        error('Có lỗi xảy ra khi lưu series')
        return false
      }
    }

    onMounted(() => {
      fetchSeries()
      fetchCategories()
    })

    return {
      series,
      loading,
      editingSeries,
      columns,
      actions,
      bulkActions,
      statusOptions,
      seriesFields,
      showCreateModal,
      truncateText,
      handleFilter,
      handleSort,
      handlePageChange,
      handleAction,
      handleBulkAction,
      handleSubmit
    }
  }
}
</script>
