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


    </DataTable>

    <!-- Create/Edit Modal -->
    <FormModal
      modal-id="roleModal"
      :title="editingRole ? 'Chỉnh sửa Role' : 'Tạo Role mới'"
      :fields="roleFields"
      :initial-data="editingRole || {}"
      icon="fas fa-user-shield"
      @submit="handleSubmit"
    >
      <!-- Custom form with all fields -->
      <template #form="{ form, errors }">
        <div class="mb-3">
          <label for="name" class="form-label">
            Tên role <span class="text-danger">*</span>
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.name }"
            placeholder="Nhập tên role (ví dụ: editor)"
            required
          >
          <div class="form-text">Tên role không được chứa khoảng trắng và ký tự đặc biệt</div>
          <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
        </div>

        <div class="mb-3">
          <label for="title" class="form-label">
            Tiêu đề <span class="text-danger">*</span>
          </label>
          <input
            id="title"
            v-model="form.title"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.title }"
            placeholder="Nhập tiêu đề role (ví dụ: Biên tập viên)"
            required
          >
          <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Mô tả</label>
          <textarea
            id="description"
            v-model="form.description"
            class="form-control"
            :class="{ 'is-invalid': errors.description }"
            rows="3"
            placeholder="Nhập mô tả về vai trò này"
          ></textarea>
          <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
        </div>

        <div class="mb-3">
          <UniversalSelect
            v-model="form.status"
            label="Trạng thái"
            mode="simple"
            :required="true"
            :error="errors.status"
            :options="statusOptions"
            help="Chọn trạng thái hoạt động của vai trò này"
          />
        </div>

        <div class="mb-3">
          <UniversalSelect
            label="Quyền hạn"
            placeholder="Chọn quyền hạn cho role này..."
            mode="advanced"
            :multiple="true"
            v-model="form.permissions"
            api-url="/v1/admin/permissions/list"
            search-param="search"
            :limit="50"
            :error="errors.permissions"
            help="Chọn các quyền hạn cho role này. Gõ để tìm kiếm hoặc cuộn để tải thêm."
          />
        </div>
      </template>
    </FormModal>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import DataTable from '../../../components/DataTable.vue'
import FormModal from '../../../components/FormModal.vue'
import UniversalSelect from '../../../components/UniversalSelect.vue'
import { useApi } from '../../../composables/useApi'
import { useToast } from '../../../composables/useToast'
import { Status, statusToString, StatusOptions } from '../../../enums/Status.js'

export default {
  name: 'AdminRolesIndex',
  components: {
    DataTable,
    FormModal,
    UniversalSelect
  },
  setup() {
    const { fetchList, create, update, remove, bulkDelete } = useApi()
    const { success, error } = useToast()

    const roles = ref({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
    const loading = ref(false)
    const editingRole = ref(null)

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
      { key: 'name', label: 'Tên', sortable: true },
      { key: 'title', label: 'Tiêu đề', sortable: true },
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
    const roleFields = computed(() => [])

    const fetchRoles = async (filters = {}) => {
      loading.value = true
      try {
        const apiUrl = window.Laravel?.routes?.api?.admin?.roles?.list || '/api/v1/admin/roles/list'
        const response = await fetchList(apiUrl.replace(window.Laravel.apiUrl, ''), filters)
        if (response && response.data) {
          roles.value = response
        }
      } catch (err) {
        console.error('Error fetching roles:', err)
        roles.value = {
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
      editingRole.value = {
        name: '',
        title: '',
        description: '',
        status: statusToString(Status.ACTIVE),
        permissions: []
      }
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('roleModal'))
      modal.show()
    }

    const showEditModal = async (role) => {
      editingRole.value = {
        id: role.id,
        name: role.name,
        title: role.title,
        description: role.description || '',
        status: statusToString(role.status || Status.ACTIVE),
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
    })

    return {
      roles,
      loading,
      editingRole,
      columns,
      actions,
      bulkActions,
      roleFields,
      statusOptions,
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
