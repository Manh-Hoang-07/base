<template>
  <div class="test-layout">
    <!-- Header -->
    <header class="bg-primary text-white p-3">
      <div class="container-fluid">
        <h1 class="h4 mb-0">🧪 Vue.js Test Layout</h1>
        <small>Testing Vue.js SPA functionality</small>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container-fluid py-4">
      <!-- Debug Info -->
      <div class="alert alert-info mb-4">
        <h5><i class="fas fa-info-circle me-2"></i>Vue.js Status</h5>
        <div class="row">
          <div class="col-md-3">
            <strong>Vue App:</strong> ✅ Running
          </div>
          <div class="col-md-3">
            <strong>Router:</strong> ✅ Active
          </div>
          <div class="col-md-3">
            <strong>Current Route:</strong> {{ $route.path }}
          </div>
          <div class="col-md-3">
            <strong>Time:</strong> {{ currentTime }}
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">🧭 Navigation Test</h5>
        </div>
        <div class="card-body">
          <div class="btn-group" role="group">
            <router-link to="/admin" class="btn btn-outline-primary">
              <i class="fas fa-home me-1"></i>Dashboard
            </router-link>
            <router-link to="/admin/users" class="btn btn-outline-success">
              <i class="fas fa-users me-1"></i>Users
            </router-link>
            <router-link to="/admin/roles" class="btn btn-outline-warning">
              <i class="fas fa-user-shield me-1"></i>Roles
            </router-link>
            <router-link to="/admin/posts" class="btn btn-outline-info">
              <i class="fas fa-newspaper me-1"></i>Posts
            </router-link>
          </div>
        </div>
      </div>

      <!-- Page Content -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">📄 Page Content</h5>
        </div>
        <div class="card-body">
          <router-view v-slot="{ Component }">
            <Suspense>
              <template #default>
                <component :is="Component" />
              </template>
              <template #fallback>
                <div class="text-center py-5">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="mt-3">Loading component...</p>
                </div>
              </template>
            </Suspense>
          </router-view>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-4">
      <small class="text-muted">
        Vue.js Test Layout - Component Count: {{ componentCount }} - 
        Mounted: {{ mountTime }}
      </small>
    </footer>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'

export default {
  name: 'TestLayout',
  setup() {
    const mountTime = ref('')
    const currentTime = ref('')

    const componentCount = computed(() => {
      return 'N/A' // Will be updated if needed
    })

    const updateTime = () => {
      currentTime.value = new Date().toLocaleTimeString()
    }

    onMounted(() => {
      mountTime.value = new Date().toLocaleTimeString()
      updateTime()
      
      // Update time every second
      setInterval(updateTime, 1000)
      
      console.log('TestLayout mounted successfully')
    })

    return {
      mountTime,
      currentTime,
      componentCount
    }
  }
}
</script>

<style scoped>
.test-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}

.btn-group .btn {
  margin-right: 0;
}

.router-link-active {
  background-color: var(--bs-primary) !important;
  color: white !important;
  border-color: var(--bs-primary) !important;
}

.alert {
  border-radius: 8px;
}

.card {
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
}

/* Animation for route changes */
.router-view {
  transition: opacity 0.3s ease;
}

/* Loading animation */
@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.5; }
  100% { opacity: 1; }
}

.spinner-border {
  animation: pulse 1.5s ease-in-out infinite;
}
</style>
