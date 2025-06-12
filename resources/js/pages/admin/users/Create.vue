<template>
  <div class="admin-user-create">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Tạo User mới</h1>
        <p class="text-muted">Thêm người dùng mới vào hệ thống</p>
      </div>
      <router-link to="/admin/users" class="btn btn-secondary">
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
              <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors.name }"
                placeholder="Nhập họ và tên"
                required
              >
              <div v-if="errors.name" class="invalid-feedback">
                {{ errors.name }}
              </div>
            </div>

            <!-- Email -->
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                class="form-control"
                :class="{ 'is-invalid': errors.email }"
                placeholder="Nhập email"
                required
              >
              <div v-if="errors.email" class="invalid-feedback">
                {{ errors.email }}
              </div>
            </div>

            <!-- Password -->
            <div class="col-md-6 mb-3">
              <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                class="form-control"
                :class="{ 'is-invalid': errors.password }"
                placeholder="Nhập mật khẩu"
                required
              >
              <div v-if="errors.password" class="invalid-feedback">
                {{ errors.password }}
              </div>
            </div>

            <!-- Confirm Password -->
            <div class="col-md-6 mb-3">
              <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                class="form-control"
                :class="{ 'is-invalid': errors.password_confirmation }"
                placeholder="Nhập lại mật khẩu"
                required
              >
              <div v-if="errors.password_confirmation" class="invalid-feedback">
                {{ errors.password_confirmation }}
              </div>
            </div>

            <!-- Status -->
            <div class="col-md-6 mb-3">
              <label for="status" class="form-label">Trạng thái</label>
              <select
                id="status"
                v-model="form.status"
                class="form-select"
                :class="{ 'is-invalid': errors.status }"
              >
                <option value="1">Hoạt động</option>
                <option value="0">Không hoạt động</option>
              </select>
              <div v-if="errors.status" class="invalid-feedback">
                {{ errors.status }}
              </div>
            </div>

            <!-- Roles -->
            <div class="col-md-6 mb-3">
              <label for="roles" class="form-label">Vai trò</label>
              <select
                id="roles"
                v-model="form.roles"
                class="form-select"
                :class="{ 'is-invalid': errors.roles }"
                multiple
              >
                <option v-for="role in availableRoles" :key="role.id" :value="role.id">
                  {{ role.title || role.name }}
                </option>
              </select>
              <div class="form-text">Giữ Ctrl để chọn nhiều vai trò</div>
              <div v-if="errors.roles" class="invalid-feedback">
                {{ errors.roles }}
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
            <router-link to="/admin/users" class="btn btn-secondary">
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
  name: 'AdminUserCreate',
  setup() {
    const router = useRouter()
    
    const form = reactive({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      status: 1,
      roles: []
    })
    
    const availableRoles = ref([])
    const errors = ref({})
    const errorMessage = ref('')
    const successMessage = ref('')
    const loading = ref(false)

    const fetchRoles = async () => {
      try {
        const response = await axios.get('/v1/admin/roles/list?per_page=100')
        if (response.data.data) {
          availableRoles.value = response.data.data
        }
      } catch (error) {
        console.error('Error fetching roles:', error)
      }
    }

    const validateForm = () => {
      errors.value = {}
      
      if (!form.name) {
        errors.value.name = 'Họ và tên là bắt buộc'
      } else if (form.name.length < 2) {
        errors.value.name = 'Họ và tên phải có ít nhất 2 ký tự'
      }
      
      if (!form.email) {
        errors.value.email = 'Email là bắt buộc'
      } else if (!/\S+@\S+\.\S+/.test(form.email)) {
        errors.value.email = 'Email không hợp lệ'
      }
      
      if (!form.password) {
        errors.value.password = 'Mật khẩu là bắt buộc'
      } else if (form.password.length < 6) {
        errors.value.password = 'Mật khẩu phải có ít nhất 6 ký tự'
      }
      
      if (!form.password_confirmation) {
        errors.value.password_confirmation = 'Xác nhận mật khẩu là bắt buộc'
      } else if (form.password !== form.password_confirmation) {
        errors.value.password_confirmation = 'Mật khẩu xác nhận không khớp'
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
        const response = await axios.post('/v1/admin/users/create', form)
        
        if (response.data.success) {
          successMessage.value = response.data.message
          
          // Redirect after successful creation
          setTimeout(() => {
            router.push('/admin/users')
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
      fetchRoles()
    })

    return {
      form,
      availableRoles,
      errors,
      errorMessage,
      successMessage,
      loading,
      handleSubmit
    }
  }
}
</script>

<style scoped>
.form-label {
  font-weight: 600;
  color: #5a5c69;
}

.text-danger {
  color: #e74a3b !important;
}

.card {
  border: none;
  box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.btn-primary {
  background: linear-gradient(45deg, #007bff, #0056b3);
  border: none;
}

.btn-primary:hover {
  background: linear-gradient(45deg, #0056b3, #004085);
}
</style>
