<template>
  <div class="auth-layout">
    <div class="container-fluid h-100">
      <div class="row h-100">
        <!-- Left side - Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
          <div class="auth-form-container">
            <div class="text-center mb-4">
              <img src="/images/logo.png" alt="Logo" height="60" class="mb-3">
              <h2 class="text-primary">{{ pageTitle }}</h2>
            </div>
            
            <router-view />
            
            <div class="text-center mt-4">
              <router-link to="/" class="text-muted">
                <i class="fas fa-arrow-left me-1"></i>Về trang chủ
              </router-link>
            </div>
          </div>
        </div>
        
        <!-- Right side - Background -->
        <div class="col-lg-6 d-none d-lg-block auth-bg">
          <div class="auth-bg-overlay">
            <div class="auth-bg-content">
              <h3 class="text-white mb-3">Chào mừng bạn đến với hệ thống</h3>
              <p class="text-white-50">
                Quản lý nội dung hiệu quả với công nghệ hiện đại
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

export default {
  name: 'AuthLayout',
  setup() {
    const route = useRoute()
    
    const pageTitle = computed(() => {
      switch (route.name) {
        case 'login':
          return 'Đăng nhập'
        case 'register':
          return 'Đăng ký'
        default:
          return 'Xác thực'
      }
    })

    return {
      pageTitle
    }
  }
}
</script>

<style scoped>
.auth-layout {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.auth-form-container {
  width: 100%;
  max-width: 400px;
  padding: 2rem;
  background: white;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.auth-bg {
  background: url('/images/bg-auth.jpg') center/cover;
  position: relative;
}

.auth-bg-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8));
  display: flex;
  align-items: center;
  justify-content: center;
}

.auth-bg-content {
  text-align: center;
  padding: 2rem;
}

@media (max-width: 991.98px) {
  .auth-layout {
    padding: 2rem 0;
  }
  
  .auth-form-container {
    margin: 0 1rem;
  }
}
</style>
