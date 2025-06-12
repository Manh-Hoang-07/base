<template>
  <div class="login-form">
    <form @submit.prevent="handleLogin">
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

      <!-- Remember me -->
      <div class="mb-3 form-check">
        <input
          id="remember"
          v-model="form.remember"
          type="checkbox"
          class="form-check-input"
        >
        <label for="remember" class="form-check-label">
          Ghi nhớ đăng nhập
        </label>
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
        <i v-else class="fas fa-sign-in-alt me-2"></i>
        {{ loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}
      </button>

      <!-- Links -->
      <div class="text-center">
        <p class="mb-2">
          <a href="#" class="text-muted">Quên mật khẩu?</a>
        </p>
        <p class="mb-0">
          Chưa có tài khoản? 
          <router-link to="/auth/register" class="text-primary">
            Đăng ký ngay
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
  name: 'Login',
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    
    const form = reactive({
      email: '',
      password: '',
      remember: false
    })
    
    const errors = ref({})
    const errorMessage = ref('')
    const successMessage = ref('')
    const loading = ref(false)
    const showPassword = ref(false)

    const validateForm = () => {
      errors.value = {}
      
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
      
      return Object.keys(errors.value).length === 0
    }

    const handleLogin = async () => {
      errorMessage.value = ''
      successMessage.value = ''
      
      if (!validateForm()) {
        return
      }

      loading.value = true
      
      try {
        const result = await authStore.login({
          email: form.email,
          password: form.password,
          remember: form.remember
        })

        if (result.success) {
          successMessage.value = result.message
          
          // Redirect after successful login
          setTimeout(() => {
            if (authStore.isAdmin) {
              router.push('/admin')
            } else {
              router.push('/')
            }
          }, 1000)
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
      handleLogin
    }
  }
}
</script>

<style scoped>
.login-form {
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
