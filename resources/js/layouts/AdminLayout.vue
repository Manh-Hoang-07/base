<template>
  <div class="admin-layout">
    <!-- Header -->
    <header class="admin-header navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <!-- Sidebar toggle -->
        <button
          class="btn btn-outline-light me-3"
          @click="toggleSidebar"
          type="button"
        >
          <i class="fas fa-bars"></i>
        </button>

        <!-- Logo -->
        <router-link to="/admin" class="navbar-brand">
          <img src="/images/logo-light.png" alt="Admin" height="35">
          <span class="ms-2">Admin Panel</span>
        </router-link>

        <!-- User menu -->
        <div class="navbar-nav ms-auto">
          <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle me-1"></i>
              {{ authStore.user?.name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <router-link to="/" class="dropdown-item">
                  <i class="fas fa-home me-2"></i>Trang chủ
                </router-link>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item" href="#" @click="handleLogout">
                  <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>

    <!-- Sidebar -->
    <aside class="admin-sidebar" :class="{ 'collapsed': sidebarCollapsed }">
      <nav class="sidebar-nav">
        <ul class="nav flex-column">
          <li class="nav-item">
            <router-link to="/admin" class="nav-link" active-class="active" exact>
              <i class="fas fa-tachometer-alt"></i>
              <span class="nav-text">Dashboard</span>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/admin/users" class="nav-link" active-class="active">
              <i class="fas fa-users"></i>
              <span class="nav-text">Quản lý Users</span>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/admin/roles" class="nav-link" active-class="active">
              <i class="fas fa-user-shield"></i>
              <span class="nav-text">Quản lý Roles</span>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/admin/posts" class="nav-link" active-class="active">
              <i class="fas fa-newspaper"></i>
              <span class="nav-text">Quản lý Posts</span>
            </router-link>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="admin-main" :class="{ 'sidebar-collapsed': sidebarCollapsed }">
      <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <Breadcrumb home-text="Admin" />

        <!-- Page content -->
        <router-view />
      </div>
    </main>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import Breadcrumb from '../components/Breadcrumb.vue'

export default {
  name: 'AdminLayout',
  components: {
    Breadcrumb
  },
  setup() {
    const authStore = useAuthStore()
    const sidebarCollapsed = ref(false)

    const toggleSidebar = () => {
      sidebarCollapsed.value = !sidebarCollapsed.value
    }

    const handleLogout = async () => {
      await authStore.logout()
    }

    return {
      authStore,
      sidebarCollapsed,
      toggleSidebar,
      handleLogout
    }
  }
}
</script>

<style scoped>
.admin-header {
  height: 60px;
  z-index: 1030;
}

.admin-sidebar {
  position: fixed;
  top: 60px;
  left: 0;
  width: 250px;
  height: calc(100vh - 60px);
  background: #2c3e50;
  transition: all 0.3s ease;
  z-index: 1020;
  overflow-y: auto;
}

.admin-sidebar.collapsed {
  width: 60px;
}

.sidebar-nav {
  padding: 1rem 0;
}

.nav-link {
  color: #bdc3c7 !important;
  padding: 0.75rem 1rem;
  display: flex;
  align-items: center;
  transition: all 0.3s ease;
}

.nav-link:hover {
  background-color: #34495e;
  color: #fff !important;
}

.nav-link.active {
  background-color: #3498db;
  color: #fff !important;
}

.nav-link i {
  width: 20px;
  margin-right: 0.5rem;
  text-align: center;
}

.admin-sidebar.collapsed .nav-text {
  display: none;
}

.admin-main {
  margin-left: 250px;
  margin-top: 60px;
  min-height: calc(100vh - 60px);
  transition: all 0.3s ease;
}

.admin-main.sidebar-collapsed {
  margin-left: 60px;
}

.breadcrumb {
  background: none;
  padding: 0;
}

.breadcrumb-item a {
  color: #3498db;
  text-decoration: none;
}

.breadcrumb-item a:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .admin-sidebar {
    transform: translateX(-100%);
  }

  .admin-sidebar.show {
    transform: translateX(0);
  }

  .admin-main {
    margin-left: 0;
  }
}
</style>
