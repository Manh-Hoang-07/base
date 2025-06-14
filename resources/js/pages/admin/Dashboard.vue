<template>
  <div class="admin-dashboard">


    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Dashboard</h1>
        <p class="text-muted">Trang quản trị Vue.js</p>
      </div>
      <div class="text-muted">
        <i class="fas fa-calendar me-1"></i>
        {{ currentDate }}
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-primary text-white shadow">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div>
                <h4 class="mb-0">{{ stats.users }}</h4>
                <p class="mb-0">Tổng Users</p>
              </div>
              <div class="align-self-center">
                <i class="fas fa-users fa-2x"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-success text-white shadow">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div>
                <h4 class="mb-0">{{ stats.posts }}</h4>
                <p class="mb-0">Tổng Posts</p>
              </div>
              <div class="align-self-center">
                <i class="fas fa-newspaper fa-2x"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-info text-white shadow">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div>
                <h4 class="mb-0">{{ stats.roles }}</h4>
                <p class="mb-0">Tổng Roles</p>
              </div>
              <div class="align-self-center">
                <i class="fas fa-user-shield fa-2x"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-warning text-white shadow">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div>
                <h4 class="mb-0">{{ stats.categories }}</h4>
                <p class="mb-0">Tổng Danh mục</p>
              </div>
              <div class="align-self-center">
                <i class="fas fa-folder fa-2x"></i>
              </div>
            </div>
          </div>
        </div>
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
                <router-link to="/admin/users" class="btn btn-primary btn-block">
                  <i class="fas fa-user-plus me-2"></i>Quản lý Users
                </router-link>
              </div>
              <div class="col-lg-3 col-md-6 mb-3">
                <router-link to="/admin/posts" class="btn btn-success btn-block">
                  <i class="fas fa-newspaper me-2"></i>Quản lý Posts
                </router-link>
              </div>
              <div class="col-lg-3 col-md-6 mb-3">
                <router-link to="/admin/categories" class="btn btn-info btn-block">
                  <i class="fas fa-folder me-2"></i>Quản lý Danh mục
                </router-link>
              </div>
              <div class="col-lg-3 col-md-6 mb-3">
                <router-link to="/admin/series" class="btn btn-warning btn-block">
                  <i class="fas fa-list me-2"></i>Quản lý Series
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
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Users gần đây</h6>
            <router-link to="/admin/users" class="btn btn-sm btn-outline-primary">
              <i class="fas fa-external-link-alt me-1"></i>Xem tất cả
            </router-link>
          </div>
          <div class="card-body">
            <div v-if="loading.users" class="text-center py-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <div v-else>
              <div v-for="user in usersData.data" :key="user.id" class="d-flex align-items-center mb-3">
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
              <div v-if="usersData.data.length === 0" class="text-center text-muted py-3">
                Chưa có users nào
              </div>

              <!-- Pagination for users -->
              <Pagination
                v-if="usersData.last_page > 1"
                :current-page="usersData.current_page"
                :total-pages="usersData.last_page"
                :total="usersData.total"
                :from="usersData.from"
                :to="usersData.to"
                :show-info="false"
                :show-first-last="false"
                size="sm"
                @page-change="handleUsersPageChange"
              />
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Posts gần đây</h6>
            <router-link to="/admin/posts" class="btn btn-sm btn-outline-primary">
              <i class="fas fa-external-link-alt me-1"></i>Xem tất cả
            </router-link>
          </div>
          <div class="card-body">
            <div v-if="loading.posts" class="text-center py-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <div v-else>
              <div v-for="post in postsData.data" :key="post.id" class="d-flex align-items-center mb-3">
                <div class="me-3">
                  <i class="fas fa-newspaper text-success"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{ post.name || post.title }}</h6>
                  <small class="text-muted">{{ truncateText(post.description, 50) }}</small>
                </div>
                <small class="text-muted">{{ formatDate(post.created_at) }}</small>
              </div>
              <div v-if="postsData.data.length === 0" class="text-center text-muted py-3">
                Chưa có posts nào
              </div>

              <!-- Pagination for posts -->
              <Pagination
                v-if="postsData.last_page > 1"
                :current-page="postsData.current_page"
                :total-pages="postsData.last_page"
                :total="postsData.total"
                :from="postsData.from"
                :to="postsData.to"
                :show-info="false"
                :show-first-last="false"
                size="sm"
                @page-change="handlePostsPageChange"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useApi } from '../../composables/useApi'
import Pagination from '../../components/Pagination.vue'

export default {
  name: 'AdminDashboard',
  components: {
    Pagination
  },
  setup() {
    const { fetchList } = useApi()

    const stats = ref({
      users: 0,
      posts: 0,
      roles: 0,
      categories: 0
    })

    const usersData = ref({
      data: [],
      current_page: 1,
      last_page: 1,
      total: 0,
      from: 0,
      to: 0
    })

    const postsData = ref({
      data: [],
      current_page: 1,
      last_page: 1,
      total: 0,
      from: 0,
      to: 0
    })

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

    const currentTime = computed(() => {
      return new Date().toLocaleTimeString('vi-VN')
    })

    const formatDate = (date) => {
      return new Intl.DateTimeFormat('vi-VN').format(new Date(date))
    }

    const truncateText = (text, length = 50) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    const fetchStats = async () => {
      loading.value.stats = true
      try {
        // Try dashboard stats API first
        const statsUrl = window.Laravel?.routes?.api?.admin?.dashboard?.stats || '/api/v1/admin/dashboard/stats'
        const statsResponse = await fetchList(statsUrl.replace(window.Laravel.apiUrl, ''))

        if (statsResponse && statsResponse.data) {
          stats.value = statsResponse.data
        } else {
          // Fallback to static stats
          stats.value = {
            users: 0,
            posts: 0,
            roles: 0,
            categories: 0,
            series: 0,
            permissions: 0
          }
        }
      } catch (err) {
        console.error('Error fetching stats:', err)
        // Fallback to static stats on error
        stats.value = {
          users: 0,
          posts: 0,
          roles: 0,
          categories: 0,
          series: 0,
          permissions: 0
        }
      } finally {
        loading.value.stats = false
      }
    }

    const fetchRecentUsers = async (page = 1) => {
      loading.value.users = true
      try {
        const apiUrl = window.Laravel?.routes?.api?.admin?.users?.list || '/api/v1/admin/users/list'
        const response = await fetchList(apiUrl.replace(window.Laravel.apiUrl, ''), {
          per_page: 5,
          page: page
        })
        if (response) {
          usersData.value = {
            data: response.data || [],
            current_page: response.current_page || 1,
            last_page: response.last_page || 1,
            total: response.total || 0,
            from: response.from || 0,
            to: response.to || 0
          }
        }
      } catch (err) {
        console.error('Error fetching recent users:', err)
      } finally {
        loading.value.users = false
      }
    }

    const fetchRecentPosts = async (page = 1) => {
      loading.value.posts = true
      try {
        const apiUrl = window.Laravel?.routes?.api?.admin?.posts?.list || '/api/v1/admin/posts/list'
        const response = await fetchList(apiUrl.replace(window.Laravel.apiUrl, ''), {
          per_page: 5,
          page: page
        })
        if (response) {
          postsData.value = {
            data: response.data || [],
            current_page: response.current_page || 1,
            last_page: response.last_page || 1,
            total: response.total || 0,
            from: response.from || 0,
            to: response.to || 0
          }
        }
      } catch (err) {
        console.error('Error fetching recent posts:', err)
      } finally {
        loading.value.posts = false
      }
    }

    const handleUsersPageChange = (page) => {
      fetchRecentUsers(page)
    }

    const handlePostsPageChange = (page) => {
      fetchRecentPosts(page)
    }

    onMounted(() => {
      fetchStats()
      fetchRecentUsers()
      fetchRecentPosts()
    })

    return {
      stats,
      usersData,
      postsData,
      loading,
      currentDate,
      currentTime,
      formatDate,
      truncateText,
      handleUsersPageChange,
      handlePostsPageChange
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
