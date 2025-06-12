<template>
  <div class="admin-post-create">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Tạo Post mới</h1>
        <p class="text-muted">Thêm bài viết mới vào hệ thống</p>
      </div>
      <router-link to="/admin/posts" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại
      </router-link>
    </div>

    <!-- Form -->
    <div class="card">
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="row">
            <!-- Title -->
            <div class="col-12 mb-3">
              <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors.title }"
                placeholder="Nhập tiêu đề bài viết"
                required
              >
              <div v-if="errors.title" class="invalid-feedback">
                {{ errors.title }}
              </div>
            </div>

            <!-- Description -->
            <div class="col-12 mb-3">
              <label for="description" class="form-label">Mô tả</label>
              <textarea
                id="description"
                v-model="form.description"
                class="form-control"
                :class="{ 'is-invalid': errors.description }"
                rows="3"
                placeholder="Nhập mô tả ngắn cho bài viết"
              ></textarea>
              <div v-if="errors.description" class="invalid-feedback">
                {{ errors.description }}
              </div>
            </div>

            <!-- Content -->
            <div class="col-12 mb-3">
              <label for="content" class="form-label">Nội dung</label>
              <textarea
                id="content"
                v-model="form.content"
                class="form-control"
                :class="{ 'is-invalid': errors.content }"
                rows="10"
                placeholder="Nhập nội dung bài viết"
              ></textarea>
              <div v-if="errors.content" class="invalid-feedback">
                {{ errors.content }}
              </div>
            </div>

            <!-- Status -->
            <div class="col-md-6 mb-3">
              <label for="status" class="form-label">Trạng thái</label>
              <select
                id="status"
                v-model="form.status"
                class="form-select"
                :class="{ 'is-invalid': errors.status }"
              >
                <option value="0">Nháp</option>
                <option value="1">Xuất bản</option>
              </select>
              <div v-if="errors.status" class="invalid-feedback">
                {{ errors.status }}
              </div>
            </div>
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

          <!-- Submit buttons -->
          <div class="d-flex justify-content-end gap-2">
            <router-link to="/admin/posts" class="btn btn-secondary">
              Hủy
            </router-link>
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="loading"
            >
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              <i v-else class="fas fa-save me-2"></i>
              {{ loading ? 'Đang lưu...' : 'Lưu' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'AdminPostCreate',
  setup() {
    const router = useRouter()
    
    const form = reactive({
      title: '',
      description: '',
      content: '',
      status: 0
    })
    
    const errors = ref({})
    const errorMessage = ref('')
    const successMessage = ref('')
    const loading = ref(false)

    const validateForm = () => {
      errors.value = {}
      
      if (!form.title) {
        errors.value.title = 'Tiêu đề là bắt buộc'
      }
      
      return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
      errorMessage.value = ''
      successMessage.value = ''
      
      if (!validateForm()) {
        return
      }

      loading.value = true
      
      try {
        const response = await axios.post('/v1/admin/posts/create', form)
        
        if (response.data.success) {
          successMessage.value = response.data.message
          
          setTimeout(() => {
            router.push('/admin/posts')
          }, 1500)
        } else {
          errorMessage.value = response.data.message
        }
      } catch (error) {
        if (error.response?.data?.errors) {
          errors.value = error.response.data.errors
        } else {
          errorMessage.value = error.response?.data?.message || 'Có lỗi xảy ra, vui lòng thử lại'
        }
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
      handleSubmit
    }
  }
}
</script>
