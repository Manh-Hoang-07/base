<template>
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">
          <i class="fas fa-key me-2"></i>Quản lý Quyền
        </h1>
        <p class="mb-0 text-muted">Quản lý quyền hạn trong hệ thống</p>
      </div>
      <button @click="showCreateModal" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm Quyền
      </button>
    </div>

    <!-- Data Table -->
    <DataTable
      :data="permissions"
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
          <strong>{{ item.title || item.name }}</strong>
          <div class="text-muted small">{{ item.name }}</div>
        </div>
      </template>

      <!-- Custom column: description -->
      <template #column-description="{ item }">
        <span class="text-muted">{{ truncateText(item.description, 80) }}</span>
      </template>

      <!-- Custom column: guard_name -->
      <template #column-guard_name="{ item }">
        <span class="badge bg-info">{{ item.guard_name || 'web' }}</span>
      </template>
    </DataTable>

    <!-- Create/Edit Modal -->
    <FormModal
      modal-id="permissionModal"
      :title="editingPermission ? 'Chỉnh sửa Quyền' : 'Tạo Quyền mới'"
      :fields="permissionFields"
      :initial-data="editingPermission || {}"
      icon="fas fa-key"
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
  name: 'AdminPermissionsIndex',
  components: {
    DataTable,
    FormModal
  },
  setup() {
    const { fetchList, create, update, remove, bulkDelete } = useApi()
    const { success, error } = useToast()

    const permissions = ref({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
    const loading = ref(false)
    const editingPermission = ref(null)

    // Table configuration
    const columns = [
      { key: 'id', label: 'ID', sortable: true },
      { key: 'name', label: 'Tên Quyền', sortable: true },
      { key: 'description', label: 'Mô tả' },
      { key: 'guard_name', label: 'Guard' },
      { key: 'created_at', label: 'Ngày tạo', type: 'date', sortable: true }
    ]

    const actions = [
      { name: 'edit', icon: 'fas fa-edit', class: 'btn btn-outline-primary', title: 'Chỉnh sửa' },
      { name: 'delete', icon: 'fas fa-trash', class: 'btn btn-outline-danger', title: 'Xóa' }
    ]

    const bulkActions = [
      { name: 'bulk-delete', label: 'Xóa đã chọn', icon: 'fas fa-trash', class: 'btn btn-outline-danger btn-sm' }
    ]

    const statusOptions = []

    // Form fields for modal
    const permissionFields = computed(() => [
      {
        name: 'name',
        label: 'Tên Quyền',
        type: 'text',
        required: true,
        placeholder: 'Nhập tên quyền (ví dụ: view_users)'
      },
      {
        name: 'title',
        label: 'Tiêu đề',
        type: 'text',
        placeholder: 'Nhập tiêu đề hiển thị (ví dụ: Xem người dùng)'
      },
      {
        name: 'description',
        label: 'Mô tả',
        type: 'textarea',
        rows: 3,
        placeholder: 'Nhập mô tả chi tiết về quyền này'
      },
      {
        name: 'guard_name',
        label: 'Guard',
        type: 'select',
        options: [
          { value: 'web', label: 'Web' },
          { value: 'api', label: 'API' }
        ],
        default: 'web'
      }
    ])

    const fetchPermissions = async (filters = {}) => {
      loading.value = true
      try {
        const apiUrl = window.Laravel?.routes?.api?.admin?.permissions?.list || '/api/v1/admin/permissions/list'
        const response = await fetchList(apiUrl.replace(window.Laravel.apiUrl, ''), filters)
        if (response && response.data) {
          permissions.value = response
        } else {
          permissions.value = {
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            from: 0,
            to: 0
          }
        }
      } catch (err) {
        console.error('Error fetching permissions:', err)
        permissions.value = {
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

    const showCreateModal = async () => {
      editingPermission.value = null
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('permissionModal'))
      modal.show()
    }

    const showEditModal = async (permission) => {
      editingPermission.value = {
        ...permission
      }
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('permissionModal'))
      modal.show()
    }

    const truncateText = (text, length = 80) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    // Event handlers
    const handleFilter = (filters) => {
      fetchPermissions(filters)
    }

    const handleSort = (sort) => {
      fetchPermissions({ sort: sort.column, order: sort.order })
    }

    const handlePageChange = (page) => {
      fetchPermissions({ page })
    }

    const handleAction = async (action, permission) => {
      switch (action) {
        case 'edit':
          showEditModal(permission)
          break
        case 'delete':
          if (confirm(`Bạn có chắc chắn muốn xóa quyền "${permission.title || permission.name}"?`)) {
            try {
              await remove('/v1/admin/permissions/delete', permission.id)
              const index = permissions.value.data.findIndex(p => p.id === permission.id)
              if (index > -1) {
                permissions.value.data.splice(index, 1)
              }
              success('Xóa quyền thành công')
            } catch (err) {
              console.error('Error deleting permission:', err)
              error('Có lỗi xảy ra khi xóa quyền')
            }
          }
          break
      }
    }

    const handleBulkAction = async (action, selectedIds) => {
      switch (action) {
        case 'bulk-delete':
          if (confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} quyền đã chọn?`)) {
            try {
              await bulkDelete('/v1/admin/permissions', selectedIds)
              fetchPermissions()
              success(`Đã xóa ${selectedIds.length} quyền`)
            } catch (err) {
              console.error('Error bulk deleting permissions:', err)
              error('Có lỗi xảy ra khi xóa quyền')
            }
          }
          break
      }
    }

    const handleSubmit = async (formData) => {
      try {
        if (editingPermission.value) {
          await update('/v1/admin/permissions/update', editingPermission.value.id, formData)
          success('Cập nhật quyền thành công')
        } else {
          await create('/v1/admin/permissions/create', formData)
          success('Tạo quyền thành công')
        }
        fetchPermissions()
        return true
      } catch (err) {
        console.error('Error submitting form:', err)
        error('Có lỗi xảy ra khi lưu quyền')
        return false
      }
    }

    onMounted(() => {
      fetchPermissions()
    })

    return {
      permissions,
      loading,
      editingPermission,
      columns,
      actions,
      bulkActions,
      statusOptions,
      permissionFields,
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
