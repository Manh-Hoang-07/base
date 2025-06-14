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
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useApi } from '../../composables/useApi'
import { useToast } from '../../composables/useToast'

export default {
  name: 'AdminProfile',
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

    const formatDate = (dateString) => {
      if (!dateString) return 'Chưa có thông tin'
      return new Date(dateString).toLocaleDateString('vi-VN')
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

    onMounted(() => {
      fetchProfile()
    })

    return {
      profileForm,
      passwordForm,
      userStats,
      userRoles,
      loading,
      formatDate,
      handleAvatarChange,
      updateProfile,
      changePassword
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
</style>
