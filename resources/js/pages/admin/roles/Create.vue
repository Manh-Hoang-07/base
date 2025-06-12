<template>
  <div class="admin-role-create">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Tạo Role mới</h1>
        <p class="text-muted">Thêm vai trò mới vào hệ thống</p>
      </div>
      <router-link to="/admin/roles" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại
      </router-link>
    </div>

    <!-- Form -->
    <div class="card">
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
                placeholder="Nhập tên role (ví dụ: editor)"
                required
              >
              <div class="form-text">Tên role không được chứa khoảng trắng và ký tự đặc biệt</div>
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
                placeholder="Nhập tiêu đề role (ví dụ: Biên tập viên)"
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
              <div v-if="errors.permissions" class="invalid-feedback d-block">
                {{ errors.permissions }}
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
              :disabled="loading"
            >
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              <i v-else class="fas fa-save me-2"></i>
              {{ loading ? 'Đang lưu...' : 'Lưu' }}
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
  name: 'AdminRoleCreate',
  setup() {
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

    const validateForm = () => {
      errors.value = {}
      
      if (!form.name) {
        errors.value.name = 'Tên role là bắt buộc'
      } else if (!/^[a-z_]+$/.test(form.name)) {
        errors.value.name = 'Tên role chỉ được chứa chữ thường và dấu gạch dưới'
      }
      
      if (!form.title) {
        errors.value.title = 'Tiêu đề là bắt buộc'
      }
      
      return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
      errorMessage.value = ''
      successMessage.value = ''
      
      if (!validateForm()) {
        return
      }

      loading.value = true
      
      try {
        const response = await axios.post('/v1/admin/roles/create', form)
        
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
        loading.value = false
      }
    }

    onMounted(() => {
      fetchPermissions()
    })

    return {
      form,
      availablePermissions,
      errors,
      errorMessage,
      successMessage,
      loading,
      handleSubmit
    }
  }
}
</script>
