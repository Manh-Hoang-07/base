<template>
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">
          <i class="fas fa-user-circle me-2"></i>Hồ sơ cá nhân
        </h1>
        <p class="mb-0 text-muted">Quản lý thông tin cá nhân và cài đặt tài khoản</p>
      </div>
    </div>

    <div class="row">
      <!-- Profile Information -->
      <div class="col-lg-8">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-user me-2"></i>Thông tin cá nhân
            </h6>
          </div>
          <div class="card-body">
            <form @submit.prevent="updateProfile">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="name" class="form-label">Họ và tên</label>
                  <input
                    type="text"
                    class="form-control"
                    id="name"
                    v-model="profileForm.name"
                    required
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="profileForm.email"
                    required
                  >
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="phone" class="form-label">Số điện thoại</label>
                  <input
                    type="tel"
                    class="form-control"
                    id="phone"
                    v-model="profileForm.phone"
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="birthday" class="form-label">Ngày sinh</label>
                  <input
                    type="date"
                    class="form-control"
                    id="birthday"
                    v-model="profileForm.birthday"
                  >
                </div>
              </div>

              <div class="mb-3">
                <label for="bio" class="form-label">Giới thiệu</label>
                <textarea
                  class="form-control"
                  id="bio"
                  rows="3"
                  v-model="profileForm.bio"
                  placeholder="Viết vài dòng giới thiệu về bản thân..."
                ></textarea>
              </div>

              <div class="mb-3">
                <label for="avatar" class="form-label">Ảnh đại diện</label>
                <input
                  type="file"
                  class="form-control"
                  id="avatar"
                  accept="image/*"
                  @change="handleAvatarChange"
                >
                <small class="form-text text-muted">Chọn file ảnh (JPG, PNG, GIF). Tối đa 2MB.</small>
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" :disabled="loading.profile">
                  <i class="fas fa-save me-2"></i>
                  <span v-if="loading.profile">Đang lưu...</span>
                  <span v-else>Cập nhật thông tin</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Change Password -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-lock me-2"></i>Đổi mật khẩu
            </h6>
          </div>
          <div class="card-body">
            <form @submit.prevent="changePassword">
              <div class="mb-3">
                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                <input
                  type="password"
                  class="form-control"
                  id="current_password"
                  v-model="passwordForm.current_password"
                  required
                >
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="new_password" class="form-label">Mật khẩu mới</label>
                  <input
                    type="password"
                    class="form-control"
                    id="new_password"
                    v-model="passwordForm.new_password"
                    required
                    minlength="8"
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                  <input
                    type="password"
                    class="form-control"
                    id="confirm_password"
                    v-model="passwordForm.confirm_password"
                    required
                    minlength="8"
                  >
                </div>
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-warning" :disabled="loading.password">
                  <i class="fas fa-key me-2"></i>
                  <span v-if="loading.password">Đang cập nhật...</span>
                  <span v-else>Đổi mật khẩu</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Profile Summary -->
      <div class="col-lg-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-id-card me-2"></i>Thông tin tài khoản
            </h6>
          </div>
          <div class="card-body text-center">
            <div class="mb-3">
              <div class="avatar-lg mx-auto mb-3">
                <img
                  v-if="profileForm.avatar"
                  :src="profileForm.avatar"
                  alt="Avatar"
                  class="rounded-circle"
                  style="width: 120px; height: 120px; object-fit: cover;"
                >
                <div
                  v-else
                  class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                  style="width: 120px; height: 120px; font-size: 48px; color: white;"
                >
                  {{ profileForm.name ? profileForm.name.charAt(0).toUpperCase() : 'U' }}
                </div>
              </div>
              <h5 class="mb-1">{{ profileForm.name || 'Chưa có tên' }}</h5>
              <p class="text-muted mb-0">{{ profileForm.email || 'Chưa có email' }}</p>
            </div>

            <div class="row text-center">
              <div class="col-6">
                <div class="border-end">
                  <h6 class="mb-0">{{ userStats.posts || 0 }}</h6>
                  <small class="text-muted">Bài viết</small>
                </div>
              </div>
              <div class="col-6">
                <h6 class="mb-0">{{ userStats.roles || 0 }}</h6>
                <small class="text-muted">Vai trò</small>
              </div>
            </div>

            <hr>

            <div class="text-start">
              <p class="mb-2">
                <i class="fas fa-calendar me-2 text-muted"></i>
                <small>Tham gia: {{ formatDate(profileForm.created_at) }}</small>
              </p>
              <p class="mb-2">
                <i class="fas fa-clock me-2 text-muted"></i>
                <small>Hoạt động cuối: {{ formatDate(profileForm.updated_at) }}</small>
              </p>
              <p class="mb-0" v-if="profileForm.phone">
                <i class="fas fa-phone me-2 text-muted"></i>
                <small>{{ profileForm.phone }}</small>
              </p>
            </div>
          </div>
        </div>

        <!-- User Roles -->
        <div class="card shadow">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-user-shield me-2"></i>Vai trò & Quyền hạn
            </h6>
          </div>
          <div class="card-body">
            <div v-if="userRoles.length > 0">
              <div v-for="role in userRoles" :key="role.id" class="mb-2">
                <span class="badge bg-info me-2">{{ role.title || role.name }}</span>
              </div>
            </div>
            <div v-else class="text-muted text-center py-3">
              Chưa có vai trò nào
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Activity History Section -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-history me-2"></i>Lịch sử hoạt động
            </h6>
            <div class="d-flex gap-2">
              <UniversalSelect
                v-model="activityFilter"
                placeholder="Lọc hoạt động"
                mode="simple"
                :options="activityFilterOptions"
                size="sm"
                @change="handleActivityFilter"
              />
              <button @click="refreshActivities" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-sync-alt"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- Loading -->
            <div v-if="loadingActivities" class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>

            <!-- Activities List -->
            <div v-else-if="activitiesData.data.length > 0">
              <div class="timeline">
                <div
                  v-for="activity in activitiesData.data"
                  :key="activity.id"
                  class="timeline-item"
                >
                  <div class="timeline-marker" :class="getActivityMarkerClass(activity.type)">
                    <i :class="getActivityIcon(activity.type)"></i>
                  </div>
                  <div class="timeline-content">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <h6 class="mb-1">{{ activity.title }}</h6>
                        <p class="text-muted mb-1">{{ activity.description }}</p>
                        <small class="text-muted">
                          <i class="fas fa-clock me-1"></i>{{ formatDateTime(activity.created_at) }}
                        </small>
                      </div>
                      <span
                        class="badge"
                        :class="getActivityBadgeClass(activity.type)"
                      >
                        {{ getActivityTypeLabel(activity.type) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pagination -->
              <div class="mt-4">
                <Pagination
                  :current-page="activitiesData.current_page"
                  :total-pages="activitiesData.last_page"
                  :total="activitiesData.total"
                  :from="activitiesData.from"
                  :to="activitiesData.to"
                  :per-page="perPage"
                  :per-page-options="[5, 10, 20, 50]"
                  :show-per-page-selector="true"
                  size="sm"
                  @page-change="handlePageChange"
                  @per-page-change="handlePerPageChange"
                />
              </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-5">
              <i class="fas fa-history fa-3x text-muted mb-3"></i>
              <h5 class="text-muted">Chưa có hoạt động nào</h5>
              <p class="text-muted">Lịch sử hoạt động của bạn sẽ hiển thị ở đây</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import Pagination from '../../components/Pagination.vue'
import UniversalSelect from '../../components/UniversalSelect.vue'
import { useApi } from '../../composables/useApi'
import { useToast } from '../../composables/useToast'

export default {
  name: 'AdminProfile',
  components: {
    Pagination,
    UniversalSelect
  },
  setup() {
    const { get, update } = useApi()
    const { success, error } = useToast()

    const profileForm = ref({
      name: '',
      email: '',
      phone: '',
      birthday: '',
      bio: '',
      avatar: null,
      created_at: null,
      updated_at: null
    })

    const passwordForm = ref({
      current_password: '',
      new_password: '',
      confirm_password: ''
    })

    const userStats = ref({
      posts: 0,
      roles: 0
    })

    const userRoles = ref([])

    const loading = ref({
      profile: false,
      password: false
    })

    // Activity tracking
    const activitiesData = ref({
      data: [],
      current_page: 1,
      last_page: 1,
      total: 0,
      from: 0,
      to: 0
    })

    const loadingActivities = ref(false)
    const activityFilter = ref('')
    const perPage = ref(10)

    // Activity filter options
    const activityFilterOptions = [
      { value: '', label: 'Tất cả hoạt động' },
      { value: 'login', label: 'Đăng nhập' },
      { value: 'post_created', label: 'Tạo bài viết' },
      { value: 'post_updated', label: 'Cập nhật bài viết' },
      { value: 'comment_created', label: 'Bình luận' },
      { value: 'profile_updated', label: 'Cập nhật hồ sơ' }
    ]

    const formatDate = (dateString) => {
      if (!dateString) return 'Chưa có thông tin'
      return new Date(dateString).toLocaleDateString('vi-VN')
    }

    const formatDateTime = (dateString) => {
      if (!dateString) return 'Chưa có thông tin'
      return new Date(dateString).toLocaleString('vi-VN')
    }

    // Activity helper functions
    const getActivityIcon = (type) => {
      const icons = {
        login: 'fas fa-sign-in-alt',
        post_created: 'fas fa-plus-circle',
        post_updated: 'fas fa-edit',
        comment_created: 'fas fa-comment',
        profile_updated: 'fas fa-user-edit',
        default: 'fas fa-circle'
      }
      return icons[type] || icons.default
    }

    const getActivityMarkerClass = (type) => {
      const classes = {
        login: 'bg-success',
        post_created: 'bg-primary',
        post_updated: 'bg-warning',
        comment_created: 'bg-info',
        profile_updated: 'bg-secondary',
        default: 'bg-light'
      }
      return classes[type] || classes.default
    }

    const getActivityBadgeClass = (type) => {
      const classes = {
        login: 'bg-success',
        post_created: 'bg-primary',
        post_updated: 'bg-warning',
        comment_created: 'bg-info',
        profile_updated: 'bg-secondary',
        default: 'bg-light'
      }
      return classes[type] || classes.default
    }

    const getActivityTypeLabel = (type) => {
      const labels = {
        login: 'Đăng nhập',
        post_created: 'Tạo bài viết',
        post_updated: 'Cập nhật bài viết',
        comment_created: 'Bình luận',
        profile_updated: 'Cập nhật hồ sơ',
        default: 'Hoạt động'
      }
      return labels[type] || labels.default
    }

    const handleAvatarChange = (event) => {
      const file = event.target.files[0]
      if (file) {
        if (file.size > 2 * 1024 * 1024) { // 2MB
          error('File ảnh quá lớn. Vui lòng chọn file nhỏ hơn 2MB.')
          return
        }
        
        const reader = new FileReader()
        reader.onload = (e) => {
          profileForm.value.avatar = e.target.result
        }
        reader.readAsDataURL(file)
      }
    }

    const fetchProfile = async () => {
      try {
        // Get current user info from Laravel global
        const currentUser = window.Laravel?.user
        if (currentUser) {
          profileForm.value = {
            ...profileForm.value,
            ...currentUser
          }
          
          if (currentUser.roles) {
            userRoles.value = currentUser.roles
            userStats.value.roles = currentUser.roles.length
          }
        }
      } catch (err) {
        console.error('Error fetching profile:', err)
      }
    }

    const updateProfile = async () => {
      loading.value.profile = true
      try {
        await update('/v1/admin/profile/update', null, profileForm.value)
        success('Cập nhật thông tin thành công')
      } catch (err) {
        console.error('Error updating profile:', err)
        error('Có lỗi xảy ra khi cập nhật thông tin')
      } finally {
        loading.value.profile = false
      }
    }

    const changePassword = async () => {
      if (passwordForm.value.new_password !== passwordForm.value.confirm_password) {
        error('Mật khẩu xác nhận không khớp')
        return
      }

      loading.value.password = true
      try {
        await update('/v1/admin/profile/change-password', null, passwordForm.value)
        success('Đổi mật khẩu thành công')

        // Reset form
        passwordForm.value = {
          current_password: '',
          new_password: '',
          confirm_password: ''
        }
      } catch (err) {
        console.error('Error changing password:', err)
        error('Có lỗi xảy ra khi đổi mật khẩu')
      } finally {
        loading.value.password = false
      }
    }

    // Mock data generator for demo
    const generateMockActivities = () => {
      const activities = []
      const types = ['login', 'post_created', 'post_updated', 'comment_created', 'profile_updated']
      const titles = {
        login: 'Đăng nhập hệ thống',
        post_created: 'Tạo bài viết mới',
        post_updated: 'Cập nhật bài viết',
        comment_created: 'Thêm bình luận',
        profile_updated: 'Cập nhật hồ sơ'
      }
      const descriptions = {
        login: 'Đăng nhập thành công vào hệ thống quản trị',
        post_created: 'Đã tạo một bài viết mới trong hệ thống',
        post_updated: 'Đã cập nhật nội dung bài viết',
        comment_created: 'Đã thêm bình luận vào bài viết',
        profile_updated: 'Đã cập nhật thông tin hồ sơ cá nhân'
      }

      for (let i = 1; i <= 10; i++) {
        const type = types[Math.floor(Math.random() * types.length)]
        const date = new Date()
        date.setDate(date.getDate() - Math.floor(Math.random() * 30))

        activities.push({
          id: i,
          type: type,
          title: titles[type],
          description: descriptions[type],
          created_at: date.toISOString()
        })
      }

      return activities
    }

    const fetchActivities = async (filters = {}) => {
      loadingActivities.value = true
      try {
        const params = {
          per_page: perPage.value,
          type: activityFilter.value,
          ...filters
        }

        // Try to fetch from API, fallback to mock data
        try {
          const response = await get('/v1/admin/profile/activities', params)
          if (response) {
            activitiesData.value = {
              data: response.data || [],
              current_page: response.current_page || 1,
              last_page: response.last_page || 1,
              total: response.total || 0,
              from: response.from || 0,
              to: response.to || 0
            }
          }
        } catch (apiError) {
          // Use mock data for demo
          const mockData = generateMockActivities()
          activitiesData.value = {
            data: mockData,
            current_page: 1,
            last_page: 3,
            total: 25,
            from: 1,
            to: 10
          }
        }
      } catch (err) {
        console.error('Error fetching activities:', err)
      } finally {
        loadingActivities.value = false
      }
    }

    const handleActivityFilter = () => {
      fetchActivities({ page: 1 })
    }

    const handlePageChange = (page) => {
      fetchActivities({ page })
    }

    const handlePerPageChange = (newPerPage) => {
      perPage.value = newPerPage
      fetchActivities({ page: 1 })
    }

    const refreshActivities = () => {
      fetchActivities({ page: activitiesData.value.current_page })
    }

    onMounted(() => {
      fetchProfile()
      fetchActivities()
    })

    return {
      profileForm,
      passwordForm,
      userStats,
      userRoles,
      loading,
      activitiesData,
      loadingActivities,
      activityFilter,
      perPage,
      activityFilterOptions,
      formatDate,
      formatDateTime,
      getActivityIcon,
      getActivityMarkerClass,
      getActivityBadgeClass,
      getActivityTypeLabel,
      handleAvatarChange,
      updateProfile,
      changePassword,
      handleActivityFilter,
      handlePageChange,
      handlePerPageChange,
      refreshActivities
    }
  }
}
</script>

<style scoped>
.avatar-lg {
  width: 120px;
  height: 120px;
}

.border-end {
  border-right: 1px solid #e3e6f0 !important;
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

/* Timeline styles */
.timeline {
  position: relative;
  padding-left: 30px;
}

.timeline::before {
  content: '';
  position: absolute;
  left: 15px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: #e3e6f0;
}

.timeline-item {
  position: relative;
  margin-bottom: 30px;
}

.timeline-marker {
  position: absolute;
  left: -22px;
  top: 5px;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 12px;
  border: 3px solid #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content {
  background: #f8f9fc;
  padding: 15px;
  border-radius: 8px;
  border-left: 3px solid #e3e6f0;
  margin-left: 15px;
}

.timeline-content h6 {
  color: #5a5c69;
  font-weight: 600;
}

.timeline-content p {
  font-size: 0.9rem;
  line-height: 1.4;
}

.timeline-item:last-child {
  margin-bottom: 0;
}

.timeline-item:last-child::after {
  content: '';
  position: absolute;
  left: -15px;
  bottom: -15px;
  width: 2px;
  height: 15px;
  background: linear-gradient(to bottom, #e3e6f0, transparent);
}
</style>
