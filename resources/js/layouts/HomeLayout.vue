<template>
  <div class="home-layout">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
      <div class="container">
        <router-link to="/" class="navbar-brand">
          <img src="/images/logo-light.png" alt="Logo" height="40">
        </router-link>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link to="/" class="nav-link" active-class="active">
                <i class="fas fa-home me-1"></i>Trang chủ
              </router-link>
            </li>
          </ul>
          
          <ul class="navbar-nav">
            <li v-if="!authStore.isAuthenticated" class="nav-item">
              <router-link to="/auth/login" class="nav-link">
                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
              </router-link>
            </li>
            <li v-if="!authStore.isAuthenticated" class="nav-item">
              <router-link to="/auth/register" class="nav-link">
                <i class="fas fa-user-plus me-1"></i>Đăng ký
              </router-link>
            </li>
            <li v-if="authStore.isAuthenticated" class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user me-1"></i>{{ authStore.user?.name }}
              </a>
              <ul class="dropdown-menu">
                <li v-if="authStore.isAdmin">
                  <router-link to="/admin" class="dropdown-item">
                    <i class="fas fa-cog me-1"></i>Quản trị
                  </router-link>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="#" @click="handleLogout">
                    <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h5>Về chúng tôi</h5>
            <p class="text-muted">Website quản lý nội dung với Laravel và Vue.js</p>
          </div>
          <div class="col-md-6 text-md-end">
            <p class="text-muted">&copy; 2024 All rights reserved.</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Back to top button -->
    <button 
      v-show="showBackToTop" 
      @click="scrollToTop" 
      class="btn btn-primary back-to-top"
    >
      <i class="fas fa-arrow-up"></i>
    </button>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '../stores/auth'

export default {
  name: 'HomeLayout',
  setup() {
    const authStore = useAuthStore()
    const showBackToTop = ref(false)

    const handleScroll = () => {
      showBackToTop.value = window.scrollY > 300
    }

    const scrollToTop = () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      })
    }

    const handleLogout = async () => {
      await authStore.logout()
      // Redirect will be handled by router guard
    }

    onMounted(() => {
      window.addEventListener('scroll', handleScroll)
    })

    onUnmounted(() => {
      window.removeEventListener('scroll', handleScroll)
    })

    return {
      authStore,
      showBackToTop,
      scrollToTop,
      handleLogout
    }
  }
}
</script>

<style scoped>
.main-content {
  margin-top: 76px; /* Account for fixed navbar */
  min-height: calc(100vh - 200px);
}

.navbar-scrolled {
  background-color: rgba(13, 110, 253, 0.95) !important;
  backdrop-filter: blur(10px);
}

.back-to-top {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  z-index: 1000;
  transition: all 0.3s ease;
}

.back-to-top:hover {
  transform: translateY(-2px);
}
</style>
