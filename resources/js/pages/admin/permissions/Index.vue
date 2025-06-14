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



      <!-- Custom column: parent -->
      <template #column-parent="{ item }">
        <span v-if="item.parent" class="badge bg-secondary">
          {{ item.parent.title || item.parent.name }}
        </span>
        <span v-else class="text-muted">-</span>
      </template>

      <!-- Custom column: guard_name -->
      <template #column-guard_name="{ item }">
        <span class="badge bg-info">{{ item.guard_name || 'web' }}</span>
      </template>

      <!-- Custom column: is_default -->
      <template #column-is_default="{ item }">
        <span v-if="item.is_default" class="badge bg-warning">
          <i class="fas fa-star me-1"></i>Mặc định
        </span>
        <span v-else class="text-muted">-</span>
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
    >
      <!-- Custom form with Select2 for parent permission -->
      <template #form="{ form, errors }">
        <div class="mb-3">
          <label for="name" class="form-label">
            Tên Quyền <span class="text-danger">*</span>
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.name }"
            placeholder="Nhập tên quyền (ví dụ: manage_users)"
            required
          >
          <div class="form-text">Tên quyền không được chứa khoảng trắng và ký tự đặc biệt</div>
          <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
        </div>

        <div class="mb-3">
          <label for="title" class="form-label">Tiêu đề</label>
          <input
            id="title"
            v-model="form.title"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.title }"
            placeholder="Nhập tiêu đề hiển thị (ví dụ: Xem người dùng)"
          >
          <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
        </div>



        <div class="mb-3">
          <Select2
            label="Quyền cha"
            placeholder="Chọn quyền cha (tùy chọn)..."
            :multiple="false"
            v-model="form.parent_id"
            api-url="/v1/admin/permissions/list?for_select=true"
            search-param="search"
            :limit="50"
            help="Chọn quyền cha để tạo cấu trúc phân cấp quyền hạn"
          />
        </div>

        <div class="mb-3">
          <label for="guard_name" class="form-label">Guard</label>
          <select
            id="guard_name"
            v-model="form.guard_name"
            class="form-select"
            :class="{ 'is-invalid': errors.guard_name }"
          >
            <option value="web">Web</option>
            <option value="api">API</option>
          </select>
          <div v-if="errors.guard_name" class="invalid-feedback">{{ errors.guard_name }}</div>
        </div>

        <SelectField
          v-model="form.status"
          label="Trạng thái"
          :required="true"
          :error="errors.status"
          :options="statusOptions"
          help="Chọn trạng thái hoạt động của quyền này"
        />

        <div class="mb-3">
          <div class="form-check">
            <input
              id="is_default"
              v-model="form.is_default"
              type="checkbox"
              class="form-check-input"
              :class="{ 'is-invalid': errors.is_default }"
            >
            <label for="is_default" class="form-check-label">
              Quyền mặc định
            </label>
          </div>
          <div class="form-text">Quyền mặc định sẽ được gán tự động cho người dùng mới</div>
          <div v-if="errors.is_default" class="invalid-feedback">{{ errors.is_default }}</div>
        </div>
      </template>
    </FormModal>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import DataTable from '../../../components/DataTable.vue'
import FormModal from '../../../components/FormModal.vue'
import Select2 from '../../../components/Select2.vue'
import SelectField from '../../../components/SelectField.vue'
import { useApi } from '../../../composables/useApi'
import { useToast } from '../../../composables/useToast'
import { Status, statusToString, StatusOptions } from '../../../enums/Status.js'

export default {
  name: 'AdminPermissionsIndex',
  components: {
    DataTable,
    FormModal,
    Select2,
    SelectField
  },
  setup() {
    const { fetchList, create, update, remove, bulkDelete } = useApi()
    const { success, error } = useToast()

    const permissions = ref({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
    const loading = ref(false)
    const editingPermission = ref(null)

    // Status options for SelectField
    const statusOptions = computed(() => {
      return StatusOptions.map(option => ({
        value: String(option.value),
        label: option.label
      }))
    })

    // Table configuration
    const columns = [
      { key: 'id', label: 'ID', sortable: true },
      { key: 'name', label: 'Tên Quyền', sortable: true },
      { key: 'parent', label: 'Quyền cha' },
      { key: 'guard_name', label: 'Guard' },
      { key: 'is_default', label: 'Quyền mặc định', type: 'boolean' },
      { key: 'status', label: 'Trạng thái', type: 'status' },
      { key: 'created_at', label: 'Ngày tạo', type: 'date', sortable: true }
    ]

    const actions = [
      { name: 'edit', icon: 'fas fa-edit', class: 'btn btn-outline-primary', title: 'Chỉnh sửa' },
      { name: 'delete', icon: 'fas fa-trash', class: 'btn btn-outline-danger', title: 'Xóa' }
    ]

    const bulkActions = [
      { name: 'bulk-delete', label: 'Xóa đã chọn', icon: 'fas fa-trash', class: 'btn btn-outline-danger btn-sm' }
    ]

    // Form fields for modal (empty since we use custom template)
    const permissionFields = computed(() => [])

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
      editingPermission.value = {
        name: '',
        title: '',
        parent_id: null,
        guard_name: 'web',
        status: statusToString(Status.ACTIVE),
        is_default: false
      }
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('permissionModal'))
      modal.show()
    }

    const showEditModal = async (permission) => {
      editingPermission.value = {
        id: permission.id,
        name: permission.name,
        title: permission.title,
        parent_id: permission.parent_id,
        guard_name: permission.guard_name || 'web',
        status: permission.status !== undefined ? statusToString(permission.status) : statusToString(Status.ACTIVE),
        is_default: Boolean(permission.is_default)
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
