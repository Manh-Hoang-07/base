<template>
  <div class="admin-dashboard">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Dashboard</h1>
        <p class="text-muted">Chào mừng bạn đến với trang quản trị</p>
      </div>
      <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>
        {{ currentDate }}
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <StatsCard
          title="Tổng Users"
          :value="stats.users"
          icon="fas fa-users"
          color="primary"
          :loading="loading.stats"
          :trend="{ value: 12, type: 'percent', period: 'so với tháng trước', direction: 'up' }"
        />
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <StatsCard
          title="Tổng Posts"
          :value="stats.posts"
          icon="fas fa-newspaper"
          color="success"
          :loading="loading.stats"
          :trend="{ value: 8, type: 'percent', period: 'so với tuần trước', direction: 'up' }"
        />
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <StatsCard
          title="Tổng Roles"
          :value="stats.roles"
          icon="fas fa-user-shield"
          color="info"
          :loading="loading.stats"
        />
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <StatsCard
          title="Online Users"
          :value="stats.online"
          icon="fas fa-circle"
          color="warning"
          :loading="loading.stats"
          subtitle="Đang hoạt động"
        />
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thao tác nhanh</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-3 col-md-6 mb-3">
                <router-link to="/admin/users/create" class="btn btn-primary btn-block">
                  <i class="fas fa-user-plus me-2"></i>Tạo User mới
                </router-link>
              </div>
              <div class="col-lg-3 col-md-6 mb-3">
                <router-link to="/admin/posts/create" class="btn btn-success btn-block">
                  <i class="fas fa-plus me-2"></i>Tạo Post mới
                </router-link>
              </div>
              <div class="col-lg-3 col-md-6 mb-3">
                <router-link to="/admin/roles/create" class="btn btn-info btn-block">
                  <i class="fas fa-shield-alt me-2"></i>Tạo Role mới
                </router-link>
              </div>
              <div class="col-lg-3 col-md-6 mb-3">
                <router-link to="/" class="btn btn-secondary btn-block">
                  <i class="fas fa-home me-2"></i>Xem trang chủ
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
      <div class="col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Users gần đây</h6>
          </div>
          <div class="card-body">
            <div v-if="loading.users" class="text-center py-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <div v-else>
              <div v-for="user in recentUsers" :key="user.id" class="d-flex align-items-center mb-3">
                <div class="avatar me-3">
                  <div class="avatar-initial bg-primary rounded-circle">
                    {{ user.name.charAt(0).toUpperCase() }}
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{ user.name }}</h6>
                  <small class="text-muted">{{ user.email }}</small>
                </div>
                <small class="text-muted">{{ formatDate(user.created_at) }}</small>
              </div>
              <div v-if="recentUsers.length === 0" class="text-center text-muted py-3">
                Chưa có users nào
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Posts gần đây</h6>
          </div>
          <div class="card-body">
            <div v-if="loading.posts" class="text-center py-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <div v-else>
              <div v-for="post in recentPosts" :key="post.id" class="d-flex align-items-center mb-3">
                <div class="me-3">
                  <i class="fas fa-newspaper text-success"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{ post.title }}</h6>
                  <small class="text-muted">{{ truncateText(post.description, 50) }}</small>
                </div>
                <small class="text-muted">{{ formatDate(post.created_at) }}</small>
              </div>
              <div v-if="recentPosts.length === 0" class="text-center text-muted py-3">
                Chưa có posts nào
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import StatsCard from '../../components/StatsCard.vue'
import { useApi } from '../../composables/useApi'

export default {
  name: 'AdminDashboard',
  components: {
    StatsCard
  },
  setup() {
    const { fetchList } = useApi()
    const stats = ref({
      users: 0,
      posts: 0,
      roles: 0,
      online: 0
    })

    const recentUsers = ref([])
    const recentPosts = ref([])

    const loading = ref({
      stats: false,
      users: false,
      posts: false
    })

    const currentDate = computed(() => {
      return new Intl.DateTimeFormat('vi-VN', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      }).format(new Date())
    })

    const fetchStats = async () => {
      loading.value.stats = true
      try {
        // Fetch basic stats - you can create a dedicated API endpoint for this
        const [usersRes, postsRes, rolesRes] = await Promise.all([
          fetchList('/v1/admin/users/list', { per_page: 1 }),
          fetchList('/v1/admin/posts/list', { per_page: 1 }),
          fetchList('/v1/admin/roles/list', { per_page: 1 })
        ])

        stats.value = {
          users: usersRes.total || 0,
          posts: postsRes.total || 0,
          roles: rolesRes.total || 0,
          online: Math.floor(Math.random() * 10) + 1 // Mock data
        }
      } catch (error) {
        console.error('Error fetching stats:', error)
      } finally {
        loading.value.stats = false
      }
    }

    const fetchRecentUsers = async () => {
      loading.value.users = true
      try {
        const data = await fetchList('/v1/admin/users/list', {
          per_page: 5,
          sort: 'created_at',
          order: 'desc'
        })
        recentUsers.value = data.data || []
      } catch (error) {
        console.error('Error fetching recent users:', error)
      } finally {
        loading.value.users = false
      }
    }

    const fetchRecentPosts = async () => {
      loading.value.posts = true
      try {
        const data = await fetchList('/v1/admin/posts/list', {
          per_page: 5,
          sort: 'created_at',
          order: 'desc'
        })
        recentPosts.value = data.data || []
      } catch (error) {
        console.error('Error fetching recent posts:', error)
      } finally {
        loading.value.posts = false
      }
    }

    const formatDate = (date) => {
      return new Intl.DateTimeFormat('vi-VN').format(new Date(date))
    }

    const truncateText = (text, length = 50) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    onMounted(() => {
      fetchStats()
      fetchRecentUsers()
      fetchRecentPosts()
    })

    return {
      stats,
      recentUsers,
      recentPosts,
      loading,
      currentDate,
      formatDate,
      truncateText
    }
  }
}
</script>

<style scoped>
.border-left-primary {
  border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
  border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
  border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
  border-left: 0.25rem solid #f6c23e !important;
}

.text-xs {
  font-size: 0.7rem;
}

.avatar {
  width: 40px;
  height: 40px;
}

.avatar-initial {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  color: white;
}

.btn-block {
  width: 100%;
}

.card {
  border: none;
  border-radius: 0.35rem;
}

.card-header {
  background-color: #f8f9fc;
  border-bottom: 1px solid #e3e6f0;
}

.shadow {
  box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
