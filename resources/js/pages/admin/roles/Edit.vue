<template>
  <div class="admin-role-edit">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Chỉnh sửa Role</h1>
        <p class="text-muted">Cập nhật thông tin vai trò</p>
      </div>
      <router-link to="/admin/roles" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại
      </router-link>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Form -->
    <div v-else class="card">
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="row">
            <!-- Name -->
            <div class="col-md-6 mb-3">
              <label for="name" class="form-label">Tên role <span class="text-danger">*</span></label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors.name }"
                placeholder="Nhập tên role"
                required
                :disabled="form.name === 'admin'"
              >
              <div v-if="form.name === 'admin'" class="form-text text-warning">
                Không thể thay đổi tên role admin
              </div>
              <div v-if="errors.name" class="invalid-feedback">
                {{ errors.name }}
              </div>
            </div>

            <!-- Title -->
            <div class="col-md-6 mb-3">
              <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors.title }"
                placeholder="Nhập tiêu đề role"
                required
              >
              <div v-if="errors.title" class="invalid-feedback">
                {{ errors.title }}
              </div>
            </div>

            <!-- Permissions -->
            <div class="col-12 mb-3">
              <label class="form-label">Quyền hạn</label>
              <div class="row">
                <div v-for="permission in availablePermissions" :key="permission.id" class="col-md-4 mb-2">
                  <div class="form-check">
                    <input
                      :id="`permission-${permission.id}`"
                      v-model="form.permissions"
                      type="checkbox"
                      class="form-check-input"
                      :value="permission.id"
                    >
                    <label :for="`permission-${permission.id}`" class="form-check-label">
                      {{ permission.title || permission.name }}
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Error message -->
          <div v-if="errorMessage" class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ errorMessage }}
          </div>

          <!-- Success message -->
          <div v-if="successMessage" class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ successMessage }}
          </div>

          <!-- Submit buttons -->
          <div class="d-flex justify-content-end gap-2">
            <router-link to="/admin/roles" class="btn btn-secondary">
              Hủy
            </router-link>
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="submitting"
            >
              <span v-if="submitting" class="spinner-border spinner-border-sm me-2"></span>
              <i v-else class="fas fa-save me-2"></i>
              {{ submitting ? 'Đang lưu...' : 'Cập nhật' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'AdminRoleEdit',
  props: {
    id: {
      type: [String, Number],
      required: true
    }
  },
  setup(props) {
    const router = useRouter()
    
    const form = reactive({
      name: '',
      title: '',
      permissions: []
    })
    
    const availablePermissions = ref([])
    const errors = ref({})
    const errorMessage = ref('')
    const successMessage = ref('')
    const loading = ref(false)
    const submitting = ref(false)

    const fetchRole = async () => {
      loading.value = true
      try {
        const response = await axios.get(`/v1/admin/roles/find/${props.id}`)
        if (response.data.success) {
          const role = response.data.data
          form.name = role.name
          form.title = role.title
          form.permissions = role.permissions ? role.permissions.map(p => p.id) : []
        }
      } catch (error) {
        errorMessage.value = 'Không tìm thấy role'
        console.error('Error fetching role:', error)
      } finally {
        loading.value = false
      }
    }

    const fetchPermissions = async () => {
      try {
        const response = await axios.get('/v1/admin/permissions/list?per_page=100')
        if (response.data.data) {
          availablePermissions.value = response.data.data
        }
      } catch (error) {
        console.error('Error fetching permissions:', error)
      }
    }

    const handleSubmit = async () => {
      errorMessage.value = ''
      successMessage.value = ''

      submitting.value = true
      
      try {
        const response = await axios.put(`/v1/admin/roles/update/${props.id}`, form)
        
        if (response.data.success) {
          successMessage.value = response.data.message
          
          setTimeout(() => {
            router.push('/admin/roles')
          }, 1500)
        } else {
          errorMessage.value = response.data.message
        }
      } catch (error) {
        if (error.response?.data?.errors) {
          errors.value = error.response.data.errors
        } else {
          errorMessage.value = error.response?.data?.message || 'Có lỗi xảy ra, vui lòng thử lại'
        }
      } finally {
        submitting.value = false
      }
    }

    onMounted(() => {
      fetchRole()
      fetchPermissions()
    })

    return {
      form,
      availablePermissions,
      errors,
      errorMessage,
      successMessage,
      loading,
      submitting,
      handleSubmit
    }
  }
}
</script>
