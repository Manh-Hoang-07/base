<template>
  <div class="post-detail">
    <!-- Loading -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="container py-5">
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ error }}
      </div>
      <router-link to="/" class="btn btn-primary">
        <i class="fas fa-arrow-left me-2"></i>Về trang chủ
      </router-link>
    </div>

    <!-- Post Content -->
    <div v-else-if="post" class="container py-5">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <!-- Post Header -->
          <div class="post-header mb-4">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <router-link to="/">Trang chủ</router-link>
                </li>
                <li class="breadcrumb-item active">{{ post.title }}</li>
              </ol>
            </nav>
            
            <h1 class="post-title">{{ post.title }}</h1>
            
            <div class="post-meta d-flex align-items-center text-muted mb-4">
              <div class="me-4">
                <i class="fas fa-calendar me-1"></i>
                {{ formatDate(post.created_at) }}
              </div>
              <div class="me-4">
                <i class="fas fa-user me-1"></i>
                {{ post.author?.name || 'Admin' }}
              </div>
              <div v-if="post.category">
                <i class="fas fa-tag me-1"></i>
                {{ post.category.name }}
              </div>
            </div>
          </div>

          <!-- Featured Image -->
          <div v-if="post.image" class="post-image mb-4">
            <img 
              :src="post.image" 
              :alt="post.title" 
              class="img-fluid rounded shadow"
            >
          </div>

          <!-- Post Description -->
          <div v-if="post.description" class="post-description mb-4">
            <div class="lead text-muted">
              {{ post.description }}
            </div>
          </div>

          <!-- Post Content -->
          <div class="post-content">
            <div v-html="post.content"></div>
          </div>

          <!-- Post Tags -->
          <div v-if="post.tags && post.tags.length" class="post-tags mt-4 pt-4 border-top">
            <h6>Tags:</h6>
            <div class="d-flex flex-wrap gap-2">
              <span 
                v-for="tag in post.tags" 
                :key="tag.id" 
                class="badge bg-secondary"
              >
                {{ tag.name }}
              </span>
            </div>
          </div>

          <!-- Navigation -->
          <div class="post-navigation mt-5 pt-4 border-top">
            <div class="row">
              <div class="col-md-6">
                <router-link 
                  v-if="previousPost" 
                  :to="{ name: 'post.detail', params: { id: previousPost.id } }"
                  class="btn btn-outline-primary"
                >
                  <i class="fas fa-arrow-left me-2"></i>
                  Bài trước
                </router-link>
              </div>
              <div class="col-md-6 text-md-end">
                <router-link 
                  v-if="nextPost" 
                  :to="{ name: 'post.detail', params: { id: nextPost.id } }"
                  class="btn btn-outline-primary"
                >
                  Bài sau
                  <i class="fas fa-arrow-right ms-2"></i>
                </router-link>
              </div>
            </div>
          </div>

          <!-- Back to list -->
          <div class="text-center mt-4">
            <router-link to="/" class="btn btn-primary">
              <i class="fas fa-list me-2"></i>Xem tất cả bài viết
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

export default {
  name: 'PostDetail',
  props: {
    id: {
      type: [String, Number],
      required: true
    }
  },
  setup(props) {
    const route = useRoute()
    const post = ref(null)
    const previousPost = ref(null)
    const nextPost = ref(null)
    const loading = ref(false)
    const error = ref('')

    const fetchPost = async (postId) => {
      loading.value = true
      error.value = ''
      
      try {
        const response = await axios.get(`/v1/posts/${postId}`)
        
        if (response.data.success) {
          post.value = response.data.data
          
          // Update page title
          document.title = `${post.value.title} - ${window.Laravel?.appName || 'Laravel Vue SPA'}`
          
          // Update meta description
          const metaDescription = document.querySelector('meta[name="description"]')
          if (metaDescription && post.value.description) {
            metaDescription.setAttribute('content', post.value.description.substring(0, 160))
          }
        } else {
          error.value = response.data.message || 'Không tìm thấy bài viết'
        }
      } catch (err) {
        if (err.response?.status === 404) {
          error.value = 'Không tìm thấy bài viết'
        } else {
          error.value = 'Có lỗi xảy ra khi tải bài viết'
        }
        console.error('Error fetching post:', err)
      } finally {
        loading.value = false
      }
    }

    const fetchRelatedPosts = async (postId) => {
      try {
        // This would be a custom API endpoint to get previous/next posts
        // For now, we'll skip this functionality
        // const response = await axios.get(`/v1/posts/${postId}/related`)
        // if (response.data.success) {
        //   previousPost.value = response.data.data.previous
        //   nextPost.value = response.data.data.next
        // }
      } catch (err) {
        console.error('Error fetching related posts:', err)
      }
    }

    const formatDate = (date) => {
      return new Intl.DateTimeFormat('vi-VN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      }).format(new Date(date))
    }

    // Watch for route changes
    watch(() => props.id, (newId) => {
      if (newId) {
        fetchPost(newId)
        fetchRelatedPosts(newId)
      }
    }, { immediate: true })

    onMounted(() => {
      // Scroll to top when component mounts
      window.scrollTo(0, 0)
    })

    return {
      post,
      previousPost,
      nextPost,
      loading,
      error,
      formatDate
    }
  }
}
</script>

<style scoped>
.post-title {
  font-size: 2.5rem;
  font-weight: 700;
  line-height: 1.2;
  color: #2c3e50;
  margin-bottom: 1rem;
}

.post-meta {
  font-size: 0.9rem;
}

.post-image img {
  width: 100%;
  height: auto;
  max-height: 500px;
  object-fit: cover;
}

.post-description {
  font-size: 1.1rem;
  line-height: 1.6;
}

.post-content {
  font-size: 1rem;
  line-height: 1.8;
  color: #333;
}

.post-content :deep(h1),
.post-content :deep(h2),
.post-content :deep(h3),
.post-content :deep(h4),
.post-content :deep(h5),
.post-content :deep(h6) {
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.post-content :deep(p) {
  margin-bottom: 1.5rem;
}

.post-content :deep(img) {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
  margin: 1rem 0;
}

.post-content :deep(blockquote) {
  border-left: 4px solid #007bff;
  padding-left: 1rem;
  margin: 1.5rem 0;
  font-style: italic;
  background-color: #f8f9fa;
  padding: 1rem;
  border-radius: 0.25rem;
}

.post-content :deep(code) {
  background-color: #f8f9fa;
  padding: 0.2rem 0.4rem;
  border-radius: 0.25rem;
  font-size: 0.9em;
}

.post-content :deep(pre) {
  background-color: #f8f9fa;
  padding: 1rem;
  border-radius: 0.5rem;
  overflow-x: auto;
  margin: 1rem 0;
}

.breadcrumb {
  background: none;
  padding: 0;
  margin-bottom: 1rem;
}

.breadcrumb-item a {
  color: #007bff;
  text-decoration: none;
}

.breadcrumb-item a:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .post-title {
    font-size: 2rem;
  }
  
  .post-meta {
    flex-direction: column;
    align-items: flex-start !important;
  }
  
  .post-meta > div {
    margin-bottom: 0.25rem;
  }
}
</style>
