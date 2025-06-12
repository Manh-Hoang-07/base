<template>
  <div class="admin-roles">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Quản lý Roles</h1>
        <p class="text-muted">Danh sách tất cả vai trò trong hệ thống</p>
      </div>
      <div class="d-flex gap-2">
        <button @click="showCreateModal" class="btn btn-primary">
          <i class="fas fa-plus me-2"></i>Thêm Role
        </button>
        <router-link to="/admin/roles/create" class="btn btn-outline-primary">
          <i class="fas fa-external-link-alt me-2"></i>Form trang
        </router-link>
      </div>
    </div>

    <!-- Data Table -->
    <DataTable
      :data="roles"
      :columns="columns"
      :actions="actions"
      :bulk-actions="bulkActions"
      :loading="loading"
      search-placeholder="Tìm theo tên, tiêu đề..."
      empty-message="Không tìm thấy role nào"
      empty-icon="fas fa-user-shield"
      selectable
      @filter="handleFilter"
      @sort="handleSort"
      @page-change="handlePageChange"
      @action="handleAction"
      @bulk-action="handleBulkAction"
    >
      <!-- Custom column: name -->
      <template #column-name="{ item }">
        <span class="badge bg-primary">{{ item.name }}</span>
      </template>

      <!-- Custom column: users_count -->
      <template #column-users_count="{ item }">
        <span class="badge bg-info">{{ item.users_count || 0 }}</span>
      </template>

      <!-- Custom column: permissions -->
      <template #column-permissions="{ item }">
        <span class="text-muted">
          {{ item.permissions ? item.permissions.length : 0 }} quyền
        </span>
      </template>
    </DataTable>

    <!-- Create/Edit Modal -->
    <FormModal
      modal-id="roleModal"
      :title="editingRole ? 'Chỉnh sửa Role' : 'Tạo Role mới'"
      :fields="roleFields"
      :initial-data="editingRole || {}"
      icon="fas fa-user-shield"
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
  name: 'AdminRolesIndex',
  components: {
    DataTable,
    FormModal
  },
  setup() {
    const { fetchList, create, update, remove, bulkDelete } = useApi()
    const { success, error } = useToast()

    const roles = ref({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
    const loading = ref(false)
    const editingRole = ref(null)
    const availablePermissions = ref([])

    // Table configuration
    const columns = [
      { key: 'id', label: 'ID', sortable: true },
      { key: 'name', label: 'Tên', sortable: true },
      { key: 'title', label: 'Tiêu đề', sortable: true },
      { key: 'users_count', label: 'Số Users' },
      { key: 'permissions', label: 'Quyền hạn' },
      { key: 'created_at', label: 'Ngày tạo', type: 'date', sortable: true }
    ]

    const actions = [
      { name: 'edit', icon: 'fas fa-edit', class: 'btn btn-outline-primary', title: 'Chỉnh sửa' },
      { name: 'delete', icon: 'fas fa-trash', class: 'btn btn-outline-danger', title: 'Xóa' }
    ]

    const bulkActions = [
      { name: 'bulk-delete', label: 'Xóa đã chọn', icon: 'fas fa-trash', class: 'btn btn-outline-danger btn-sm' }
    ]

    // Form fields for modal
    const roleFields = computed(() => [
      {
        name: 'name',
        label: 'Tên role',
        type: 'text',
        required: true,
        placeholder: 'Nhập tên role (ví dụ: editor)',
        help: 'Tên role không được chứa khoảng trắng và ký tự đặc biệt'
      },
      {
        name: 'title',
        label: 'Tiêu đề',
        type: 'text',
        required: true,
        placeholder: 'Nhập tiêu đề role (ví dụ: Biên tập viên)'
      },
      {
        name: 'permissions',
        label: 'Quyền hạn',
        type: 'select',
        multiple: true,
        options: availablePermissions.value,
        help: 'Chọn các quyền hạn cho role này'
      }
    ])

    const fetchRoles = async (filters = {}) => {
      loading.value = true
      try {
        const data = await fetchList('/v1/admin/roles/list', filters)
        roles.value = data
      } catch (err) {
        console.error('Error fetching roles:', err)
      } finally {
        loading.value = false
      }
    }

    const fetchPermissions = async () => {
      try {
        const data = await fetchList('/v1/admin/permissions/list', { per_page: 100 })
        availablePermissions.value = data.data.map(permission => ({
          value: permission.id,
          label: permission.title || permission.name,
          description: permission.description
        }))
      } catch (err) {
        console.error('Error fetching permissions:', err)
      }
    }

    const showCreateModal = async () => {
      editingRole.value = null
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('roleModal'))
      modal.show()
    }

    const showEditModal = async (role) => {
      editingRole.value = {
        ...role,
        permissions: role.permissions ? role.permissions.map(permission => permission.id) : []
      }
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('roleModal'))
      modal.show()
    }

    // Event handlers
    const handleFilter = (filters) => {
      fetchRoles(filters)
    }

    const handleSort = (sort) => {
      fetchRoles({ sort: sort.column, order: sort.order })
    }

    const handlePageChange = (page) => {
      fetchRoles({ page })
    }

    const handleAction = async (action, role) => {
      switch (action) {
        case 'edit':
          showEditModal(role)
          break
        case 'delete':
          if (role.name === 'admin') {
            error('Không thể xóa role admin')
            return
          }

          if (confirm(`Bạn có chắc chắn muốn xóa role "${role.title || role.name}"?`)) {
            try {
              await remove('/v1/admin/roles/delete', role.id)
              const index = roles.value.data.findIndex(r => r.id === role.id)
              if (index > -1) {
                roles.value.data.splice(index, 1)
              }
            } catch (err) {
              console.error('Error deleting role:', err)
            }
          }
          break
      }
    }

    const handleBulkAction = async (action, selectedIds) => {
      switch (action) {
        case 'bulk-delete':
          // Check if admin role is selected
          const adminRole = roles.value.data.find(role =>
            selectedIds.includes(role.id) && role.name === 'admin'
          )

          if (adminRole) {
            error('Không thể xóa role admin')
            return
          }

          if (confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} role đã chọn?`)) {
            try {
              await bulkDelete('/v1/admin/roles', selectedIds)
              fetchRoles()
            } catch (err) {
              console.error('Error bulk deleting:', err)
            }
          }
          break
      }
    }

    const handleSubmit = async (formData) => {
      try {
        if (editingRole.value) {
          await update('/v1/admin/roles/update', editingRole.value.id, formData)
        } else {
          await create('/v1/admin/roles/create', formData)
        }
        fetchRoles()
        return true
      } catch (err) {
        console.error('Error submitting form:', err)
        return false
      }
    }

    onMounted(() => {
      fetchRoles()
      fetchPermissions()
    })

    return {
      roles,
      loading,
      editingRole,
      columns,
      actions,
      bulkActions,
      roleFields,
      showCreateModal,
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
