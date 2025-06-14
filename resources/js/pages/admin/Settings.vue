<template>
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">
          <i class="fas fa-cog me-2"></i>Cài đặt hệ thống
        </h1>
        <p class="mb-0 text-muted">Quản lý cấu hình và cài đặt ứng dụng</p>
      </div>
    </div>

    <div class="row">
      <!-- General Settings -->
      <div class="col-lg-8">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-globe me-2"></i>Cài đặt chung
            </h6>
          </div>
          <div class="card-body">
            <form @submit.prevent="saveGeneralSettings">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="site_name" class="form-label">Tên website</label>
                  <input
                    type="text"
                    class="form-control"
                    id="site_name"
                    v-model="generalSettings.site_name"
                    required
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="site_url" class="form-label">URL website</label>
                  <input
                    type="url"
                    class="form-control"
                    id="site_url"
                    v-model="generalSettings.site_url"
                    required
                  >
                </div>
              </div>

              <div class="mb-3">
                <label for="site_description" class="form-label">Mô tả website</label>
                <textarea
                  class="form-control"
                  id="site_description"
                  rows="3"
                  v-model="generalSettings.site_description"
                  placeholder="Nhập mô tả ngắn về website..."
                ></textarea>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="admin_email" class="form-label">Email quản trị</label>
                  <input
                    type="email"
                    class="form-control"
                    id="admin_email"
                    v-model="generalSettings.admin_email"
                    required
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="timezone" class="form-label">Múi giờ</label>
                  <select class="form-control" id="timezone" v-model="generalSettings.timezone">
                    <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh (UTC+7)</option>
                    <option value="UTC">UTC (UTC+0)</option>
                    <option value="America/New_York">America/New_York (UTC-5)</option>
                    <option value="Europe/London">Europe/London (UTC+0)</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="language" class="form-label">Ngôn ngữ mặc định</label>
                  <select class="form-control" id="language" v-model="generalSettings.language">
                    <option value="vi">Tiếng Việt</option>
                    <option value="en">English</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="posts_per_page" class="form-label">Số bài viết mỗi trang</label>
                  <input
                    type="number"
                    class="form-control"
                    id="posts_per_page"
                    v-model="generalSettings.posts_per_page"
                    min="1"
                    max="100"
                  >
                </div>
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" :disabled="loading.general">
                  <i class="fas fa-save me-2"></i>
                  <span v-if="loading.general">Đang lưu...</span>
                  <span v-else>Lưu cài đặt</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Email Settings -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-envelope me-2"></i>Cài đặt Email
            </h6>
          </div>
          <div class="card-body">
            <form @submit.prevent="saveEmailSettings">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="mail_driver" class="form-label">Mail Driver</label>
                  <select class="form-control" id="mail_driver" v-model="emailSettings.mail_driver">
                    <option value="smtp">SMTP</option>
                    <option value="sendmail">Sendmail</option>
                    <option value="mailgun">Mailgun</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="mail_host" class="form-label">SMTP Host</label>
                  <input
                    type="text"
                    class="form-control"
                    id="mail_host"
                    v-model="emailSettings.mail_host"
                    placeholder="smtp.gmail.com"
                  >
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="mail_port" class="form-label">SMTP Port</label>
                  <input
                    type="number"
                    class="form-control"
                    id="mail_port"
                    v-model="emailSettings.mail_port"
                    placeholder="587"
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="mail_encryption" class="form-label">Encryption</label>
                  <select class="form-control" id="mail_encryption" v-model="emailSettings.mail_encryption">
                    <option value="">None</option>
                    <option value="tls">TLS</option>
                    <option value="ssl">SSL</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="mail_username" class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="mail_username"
                    v-model="emailSettings.mail_username"
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="mail_password" class="form-label">Password</label>
                  <input
                    type="password"
                    class="form-control"
                    id="mail_password"
                    v-model="emailSettings.mail_password"
                  >
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="mail_from_address" class="form-label">From Address</label>
                  <input
                    type="email"
                    class="form-control"
                    id="mail_from_address"
                    v-model="emailSettings.mail_from_address"
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="mail_from_name" class="form-label">From Name</label>
                  <input
                    type="text"
                    class="form-control"
                    id="mail_from_name"
                    v-model="emailSettings.mail_from_name"
                  >
                </div>
              </div>

              <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-info" @click="testEmail" :disabled="loading.test">
                  <i class="fas fa-paper-plane me-2"></i>
                  <span v-if="loading.test">Đang gửi...</span>
                  <span v-else>Test Email</span>
                </button>
                <button type="submit" class="btn btn-primary" :disabled="loading.email">
                  <i class="fas fa-save me-2"></i>
                  <span v-if="loading.email">Đang lưu...</span>
                  <span v-else>Lưu cài đặt</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- System Info -->
      <div class="col-lg-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-info-circle me-2"></i>Thông tin hệ thống
            </h6>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <strong>Phiên bản Laravel:</strong>
              <span class="float-end">{{ systemInfo.laravel_version }}</span>
            </div>
            <div class="mb-3">
              <strong>Phiên bản PHP:</strong>
              <span class="float-end">{{ systemInfo.php_version }}</span>
            </div>
            <div class="mb-3">
              <strong>Database:</strong>
              <span class="float-end">{{ systemInfo.database_type }}</span>
            </div>
            <div class="mb-3">
              <strong>Server:</strong>
              <span class="float-end">{{ systemInfo.server_software }}</span>
            </div>
            <div class="mb-3">
              <strong>Múi giờ server:</strong>
              <span class="float-end">{{ systemInfo.server_timezone }}</span>
            </div>
            <div class="mb-0">
              <strong>Dung lượng upload tối đa:</strong>
              <span class="float-end">{{ systemInfo.max_upload_size }}</span>
            </div>
          </div>
        </div>

        <!-- Cache Management -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-database me-2"></i>Quản lý Cache
            </h6>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <button class="btn btn-outline-warning" @click="clearCache('config')" :disabled="loading.cache">
                <i class="fas fa-trash me-2"></i>Xóa Config Cache
              </button>
              <button class="btn btn-outline-warning" @click="clearCache('route')" :disabled="loading.cache">
                <i class="fas fa-trash me-2"></i>Xóa Route Cache
              </button>
              <button class="btn btn-outline-warning" @click="clearCache('view')" :disabled="loading.cache">
                <i class="fas fa-trash me-2"></i>Xóa View Cache
              </button>
              <button class="btn btn-outline-danger" @click="clearCache('all')" :disabled="loading.cache">
                <i class="fas fa-trash-alt me-2"></i>Xóa tất cả Cache
              </button>
            </div>
          </div>
        </div>

        <!-- Backup -->
        <div class="card shadow">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
              <i class="fas fa-download me-2"></i>Sao lưu dữ liệu
            </h6>
          </div>
          <div class="card-body">
            <p class="text-muted small mb-3">Tạo bản sao lưu database và files</p>
            <div class="d-grid gap-2">
              <button class="btn btn-outline-success" @click="createBackup('database')" :disabled="loading.backup">
                <i class="fas fa-database me-2"></i>Backup Database
              </button>
              <button class="btn btn-outline-info" @click="createBackup('files')" :disabled="loading.backup">
                <i class="fas fa-file-archive me-2"></i>Backup Files
              </button>
              <button class="btn btn-success" @click="createBackup('full')" :disabled="loading.backup">
                <i class="fas fa-download me-2"></i>Full Backup
              </button>
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
  name: 'AdminSettings',
  setup() {
    const { get, update } = useApi()
    const { success, error } = useToast()

    const generalSettings = ref({
      site_name: 'My Website',
      site_url: 'http://web.local',
      site_description: '',
      admin_email: 'admin@example.com',
      timezone: 'Asia/Ho_Chi_Minh',
      language: 'vi',
      posts_per_page: 10
    })

    const emailSettings = ref({
      mail_driver: 'smtp',
      mail_host: '',
      mail_port: 587,
      mail_encryption: 'tls',
      mail_username: '',
      mail_password: '',
      mail_from_address: '',
      mail_from_name: ''
    })

    const systemInfo = ref({
      laravel_version: '10.x',
      php_version: '8.1',
      database_type: 'MySQL',
      server_software: 'Apache',
      server_timezone: 'UTC+7',
      max_upload_size: '2MB'
    })

    const loading = ref({
      general: false,
      email: false,
      test: false,
      cache: false,
      backup: false
    })

    const saveGeneralSettings = async () => {
      loading.value.general = true
      try {
        await update('/v1/admin/settings/general', null, generalSettings.value)
        success('Lưu cài đặt chung thành công')
      } catch (err) {
        console.error('Error saving general settings:', err)
        error('Có lỗi xảy ra khi lưu cài đặt')
      } finally {
        loading.value.general = false
      }
    }

    const saveEmailSettings = async () => {
      loading.value.email = true
      try {
        await update('/v1/admin/settings/email', null, emailSettings.value)
        success('Lưu cài đặt email thành công')
      } catch (err) {
        console.error('Error saving email settings:', err)
        error('Có lỗi xảy ra khi lưu cài đặt email')
      } finally {
        loading.value.email = false
      }
    }

    const testEmail = async () => {
      loading.value.test = true
      try {
        await get('/v1/admin/settings/test-email')
        success('Email test đã được gửi thành công')
      } catch (err) {
        console.error('Error sending test email:', err)
        error('Có lỗi xảy ra khi gửi email test')
      } finally {
        loading.value.test = false
      }
    }

    const clearCache = async (type) => {
      loading.value.cache = true
      try {
        await get(`/v1/admin/settings/clear-cache/${type}`)
        success(`Đã xóa ${type} cache thành công`)
      } catch (err) {
        console.error('Error clearing cache:', err)
        error('Có lỗi xảy ra khi xóa cache')
      } finally {
        loading.value.cache = false
      }
    }

    const createBackup = async (type) => {
      loading.value.backup = true
      try {
        await get(`/v1/admin/settings/backup/${type}`)
        success(`Đã tạo ${type} backup thành công`)
      } catch (err) {
        console.error('Error creating backup:', err)
        error('Có lỗi xảy ra khi tạo backup')
      } finally {
        loading.value.backup = false
      }
    }

    onMounted(() => {
      // Load current settings
    })

    return {
      generalSettings,
      emailSettings,
      systemInfo,
      loading,
      saveGeneralSettings,
      saveEmailSettings,
      testEmail,
      clearCache,
      createBackup
    }
  }
}
</script>

<style scoped>
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

.float-end {
  float: right;
}
</style>
