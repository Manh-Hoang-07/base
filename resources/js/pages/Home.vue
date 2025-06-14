<template>
  <div class="home-page">
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-3">Chào mừng đến với hệ thống</h1>
            <p class="lead mb-4">
              Quản lý nội dung hiệu quả với công nghệ Laravel và Vue.js
            </p>
            <div class="d-flex gap-3">
              <router-link to="/auth/register" class="btn btn-light btn-lg">
                <i class="fas fa-user-plus me-2"></i>Đăng ký ngay
              </router-link>
              <router-link to="/auth/login" class="btn btn-outline-light btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
              </router-link>
            </div>
          </div>
          <div class="col-lg-6 text-center">
            <img src="/images/startup.svg" alt="Hero" class="img-fluid" style="max-height: 400px;">
          </div>
        </div>
      </div>
    </section>

    <!-- Posts Section -->
    <section class="posts-section py-5">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h2 class="section-title">Bài viết mới nhất</h2>
              
              <!-- Search -->
              <div class="search-box">
                <div class="input-group">
                  <input 
                    v-model="searchQuery" 
                    type="text" 
                    class="form-control" 
                    placeholder="Tìm kiếm bài viết..."
                    @keyup.enter="searchPosts"
                  >
                  <button class="btn btn-primary" @click="searchPosts">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>



        <!-- Loading -->
        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>

        <!-- Posts Grid -->
        <div v-else class="row">
          <div v-for="post in posts.data" :key="post.id" class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 post-card">
              <img
                v-if="post.image"
                :src="post.image"
                class="card-img-top"
                :alt="post.name"
                style="height: 200px; object-fit: cover;"
              >
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ post.name }}</h5>
                <p class="card-text text-muted flex-grow-1">
                  {{ truncateText(post.description, 100) }}
                </p>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <small class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    {{ formatDate(post.created_at) }}
                  </small>
                  <router-link 
                    :to="{ name: 'post.detail', params: { id: post.id } }" 
                    class="btn btn-primary btn-sm"
                  >
                    Đọc thêm
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-if="!loading && posts.data?.length === 0" class="text-center py-5">
          <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
          <h4 class="text-muted">Chưa có bài viết nào</h4>
          <p class="text-muted">Hãy quay lại sau để xem nội dung mới nhất</p>
        </div>

        <!-- Pagination -->
        <div v-if="posts.last_page > 1" class="row">
          <div class="col-12">
            <nav aria-label="Posts pagination">
              <ul class="pagination justify-content-center">
                <li class="page-item" :class="{ disabled: posts.current_page === 1 }">
                  <button class="page-link" @click="changePage(posts.current_page - 1)">
                    <i class="fas fa-chevron-left"></i>
                  </button>
                </li>
                
                <li 
                  v-for="page in visiblePages" 
                  :key="page" 
                  class="page-item" 
                  :class="{ active: page === posts.current_page }"
                >
                  <button class="page-link" @click="changePage(page)">
                    {{ page }}
                  </button>
                </li>
                
                <li class="page-item" :class="{ disabled: posts.current_page === posts.last_page }">
                  <button class="page-link" @click="changePage(posts.current_page + 1)">
                    <i class="fas fa-chevron-right"></i>
                  </button>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'Home',
  setup() {
    const posts = ref({ data: [], current_page: 1, last_page: 1 })
    const loading = ref(false)
    const searchQuery = ref('')

    const fetchPosts = async (page = 1, search = '') => {
      loading.value = true
      try {
        const params = { page, per_page: 9 }
        if (search) params.search = search

        const response = await axios.get('/v1/posts', { params })
        if (response.data) {
          posts.value = response.data
        }
      } catch (error) {
        console.error('Error fetching posts:', error)
      } finally {
        loading.value = false
      }
    }

    const searchPosts = () => {
      fetchPosts(1, searchQuery.value)
    }

    const changePage = (page) => {
      if (page >= 1 && page <= posts.value.last_page) {
        fetchPosts(page, searchQuery.value)
      }
    }

    const visiblePages = computed(() => {
      const current = posts.value.current_page
      const last = posts.value.last_page
      const pages = []
      
      const start = Math.max(1, current - 2)
      const end = Math.min(last, current + 2)
      
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      
      return pages
    })

    const formatDate = (date) => {
      return new Intl.DateTimeFormat('vi-VN').format(new Date(date))
    }

    const truncateText = (text, length = 100) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    onMounted(() => {
      fetchPosts()
    })

    return {
      posts,
      loading,
      searchQuery,
      visiblePages,
      fetchPosts,
      searchPosts,
      changePage,
      formatDate,
      truncateText
    }
  }
}
</script>

<style scoped>
.hero-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.post-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border: none;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.post-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.search-box {
  max-width: 300px;
}

.section-title {
  color: #2c3e50;
  font-weight: 600;
}

.pagination .page-link {
  border-radius: 50%;
  margin: 0 2px;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #dee2e6;
}

.pagination .page-item.active .page-link {
  background-color: #007bff;
  border-color: #007bff;
}
</style>
