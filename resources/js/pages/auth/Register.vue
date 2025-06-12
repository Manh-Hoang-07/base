<template>
  <div class="register-form">
    <form @submit.prevent="handleRegister">
      <!-- Name -->
      <div class="mb-3">
        <label for="name" class="form-label">Họ và tên</label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fas fa-user"></i>
          </span>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="form-control"
            :class="{ 'is-invalid': errors.name }"
            placeholder="Nhập họ và tên"
            required
          >
        </div>
        <div v-if="errors.name" class="invalid-feedback">
          {{ errors.name }}
        </div>
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fas fa-envelope"></i>
          </span>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="form-control"
            :class="{ 'is-invalid': errors.email }"
            placeholder="Nhập email của bạn"
            required
          >
        </div>
        <div v-if="errors.email" class="invalid-feedback">
          {{ errors.email }}
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu</label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fas fa-lock"></i>
          </span>
          <input
            id="password"
            v-model="form.password"
            :type="showPassword ? 'text' : 'password'"
            class="form-control"
            :class="{ 'is-invalid': errors.password }"
            placeholder="Nhập mật khẩu"
            required
          >
          <button
            type="button"
            class="btn btn-outline-secondary"
            @click="showPassword = !showPassword"
          >
            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
          </button>
        </div>
        <div v-if="errors.password" class="invalid-feedback">
          {{ errors.password }}
        </div>
      </div>

      <!-- Confirm Password -->
      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
        <div class="input-group">
          <span class="input-group-text">
            <i class="fas fa-lock"></i>
          </span>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            :type="showConfirmPassword ? 'text' : 'password'"
            class="form-control"
            :class="{ 'is-invalid': errors.password_confirmation }"
            placeholder="Nhập lại mật khẩu"
            required
          >
          <button
            type="button"
            class="btn btn-outline-secondary"
            @click="showConfirmPassword = !showConfirmPassword"
          >
            <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
          </button>
        </div>
        <div v-if="errors.password_confirmation" class="invalid-feedback">
          {{ errors.password_confirmation }}
        </div>
      </div>

      <!-- Terms -->
      <div class="mb-3 form-check">
        <input
          id="terms"
          v-model="form.terms"
          type="checkbox"
          class="form-check-input"
          :class="{ 'is-invalid': errors.terms }"
          required
        >
        <label for="terms" class="form-check-label">
          Tôi đồng ý với <a href="#" class="text-primary">điều khoản sử dụng</a>
        </label>
        <div v-if="errors.terms" class="invalid-feedback">
          {{ errors.terms }}
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

      <!-- Submit button -->
      <button
        type="submit"
        class="btn btn-primary w-100 mb-3"
        :disabled="loading"
      >
        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
        <i v-else class="fas fa-user-plus me-2"></i>
        {{ loading ? 'Đang đăng ký...' : 'Đăng ký' }}
      </button>

      <!-- Links -->
      <div class="text-center">
        <p class="mb-0">
          Đã có tài khoản? 
          <router-link to="/auth/login" class="text-primary">
            Đăng nhập ngay
          </router-link>
        </p>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

export default {
  name: 'Register',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const form = reactive({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      terms: false
    })
    
    const errors = ref({})
    const errorMessage = ref('')
    const successMessage = ref('')
    const loading = ref(false)
    const showPassword = ref(false)
    const showConfirmPassword = ref(false)

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
      
      if (!form.terms) {
        errors.value.terms = 'Bạn phải đồng ý với điều khoản sử dụng'
      }
      
      return Object.keys(errors.value).length === 0
    }

    const handleRegister = async () => {
      errorMessage.value = ''
      successMessage.value = ''
      
      if (!validateForm()) {
        return
      }

      loading.value = true
      
      try {
        const result = await authStore.register({
          name: form.name,
          email: form.email,
          password: form.password,
          password_confirmation: form.password_confirmation
        })

        if (result.success) {
          successMessage.value = result.message
          
          // Redirect to login after successful registration
          setTimeout(() => {
            router.push('/auth/login')
          }, 2000)
        } else {
          errorMessage.value = result.message
        }
      } catch (error) {
        errorMessage.value = 'Có lỗi xảy ra, vui lòng thử lại'
      } finally {
        loading.value = false
      }
    }

    return {
      form,
      errors,
      errorMessage,
      successMessage,
      loading,
      showPassword,
      showConfirmPassword,
      handleRegister
    }
  }
}
</script>

<style scoped>
.register-form {
  width: 100%;
}

.input-group-text {
  background-color: #f8f9fa;
  border-right: none;
}

.form-control {
  border-left: none;
}

.form-control:focus {
  border-color: #80bdff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
  background: linear-gradient(45deg, #007bff, #0056b3);
  border: none;
  padding: 12px;
  font-weight: 500;
}

.btn-primary:hover {
  background: linear-gradient(45deg, #0056b3, #004085);
  transform: translateY(-1px);
}

.alert {
  border: none;
  border-radius: 8px;
}

.form-check-input:checked {
  background-color: #007bff;
  border-color: #007bff;
}

a {
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>
