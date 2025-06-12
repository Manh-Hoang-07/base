<template>
  <div class="admin-posts">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-0">Quản lý Posts</h1>
        <p class="text-muted">Danh sách tất cả bài viết trong hệ thống</p>
      </div>
      <div class="d-flex gap-2">
        <button @click="showCreateModal" class="btn btn-primary">
          <i class="fas fa-plus me-2"></i>Thêm Post
        </button>
        <router-link to="/admin/posts/create" class="btn btn-outline-primary">
          <i class="fas fa-external-link-alt me-2"></i>Form trang
        </router-link>
      </div>
    </div>

    <!-- Data Table -->
    <DataTable
      :data="posts"
      :columns="columns"
      :actions="actions"
      :bulk-actions="bulkActions"
      :loading="loading"
      :status-options="statusOptions"
      search-placeholder="Tìm theo tiêu đề, mô tả..."
      empty-message="Không tìm thấy post nào"
      empty-icon="fas fa-newspaper"
      selectable
      @filter="handleFilter"
      @sort="handleSort"
      @page-change="handlePageChange"
      @action="handleAction"
      @bulk-action="handleBulkAction"
    >
      <!-- Custom column: title -->
      <template #column-title="{ item }">
        <div>
          <strong>{{ item.title }}</strong>
          <div v-if="item.image" class="text-muted small">
            <i class="fas fa-image me-1"></i>Có hình ảnh
          </div>
        </div>
      </template>

      <!-- Custom column: description -->
      <template #column-description="{ item }">
        <span class="text-muted">{{ truncateText(item.description, 60) }}</span>
      </template>

      <!-- Custom column: status -->
      <template #column-status="{ item }">
        <span
          class="badge"
          :class="item.status ? 'bg-success' : 'bg-warning'"
        >
          {{ item.status ? 'Đã xuất bản' : 'Nháp' }}
        </span>
      </template>

      <!-- Custom column: category -->
      <template #column-category="{ item }">
        <span v-if="item.category" class="badge bg-info">
          {{ item.category.name }}
        </span>
        <span v-else class="text-muted">Chưa phân loại</span>
      </template>
    </DataTable>

    <!-- Create/Edit Modal -->
    <FormModal
      modal-id="postModal"
      :title="editingPost ? 'Chỉnh sửa Post' : 'Tạo Post mới'"
      :fields="postFields"
      :initial-data="editingPost || {}"
      icon="fas fa-newspaper"
      modal-size="modal-xl"
      @submit="handleSubmit"
    />
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import DataTable from '../../../components/DataTable.vue'
import FormModal from '../../../components/FormModal.vue'
import { useApi } from '../../../composables/useApi'
import { useToast } from '../../../composables/useToast'

export default {
  name: 'AdminPostsIndex',
  components: {
    DataTable,
    FormModal
  },
  setup() {
    const { fetchList, create, update, remove, toggleStatus, bulkDelete } = useApi()
    const { success, error } = useToast()

    const posts = ref({ data: [], current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
    const loading = ref(false)
    const editingPost = ref(null)
    const availableCategories = ref([])
    const availableSeries = ref([])

    // Table configuration
    const columns = [
      { key: 'id', label: 'ID', sortable: true },
      { key: 'title', label: 'Tiêu đề', sortable: true },
      { key: 'description', label: 'Mô tả' },
      { key: 'category', label: 'Danh mục' },
      { key: 'status', label: 'Trạng thái' },
      { key: 'created_at', label: 'Ngày tạo', type: 'date', sortable: true }
    ]

    const actions = [
      { name: 'edit', icon: 'fas fa-edit', class: 'btn btn-outline-primary', title: 'Chỉnh sửa' },
      { name: 'toggle-status', icon: 'fas fa-eye', class: 'btn btn-outline-warning', title: 'Thay đổi trạng thái' },
      { name: 'delete', icon: 'fas fa-trash', class: 'btn btn-outline-danger', title: 'Xóa' }
    ]

    const bulkActions = [
      { name: 'bulk-publish', label: 'Xuất bản', icon: 'fas fa-eye', class: 'btn btn-outline-success btn-sm' },
      { name: 'bulk-unpublish', label: 'Ẩn', icon: 'fas fa-eye-slash', class: 'btn btn-outline-warning btn-sm' },
      { name: 'bulk-delete', label: 'Xóa đã chọn', icon: 'fas fa-trash', class: 'btn btn-outline-danger btn-sm' }
    ]

    const statusOptions = [
      { value: '1', label: 'Đã xuất bản' },
      { value: '0', label: 'Nháp' }
    ]

    // Form fields for modal
    const postFields = computed(() => [
      {
        name: 'title',
        label: 'Tiêu đề',
        type: 'text',
        required: true,
        placeholder: 'Nhập tiêu đề bài viết'
      },
      {
        name: 'description',
        label: 'Mô tả',
        type: 'textarea',
        rows: 3,
        placeholder: 'Nhập mô tả ngắn cho bài viết'
      },
      {
        name: 'content',
        label: 'Nội dung',
        type: 'textarea',
        rows: 10,
        placeholder: 'Nhập nội dung bài viết'
      },
      {
        name: 'category_id',
        label: 'Danh mục',
        type: 'select',
        options: availableCategories.value,
        placeholder: 'Chọn danh mục'
      },
      {
        name: 'series_id',
        label: 'Series',
        type: 'select',
        options: availableSeries.value,
        placeholder: 'Chọn series (tùy chọn)'
      },
      {
        name: 'status',
        label: 'Trạng thái',
        type: 'select',
        options: [
          { value: 0, label: 'Nháp' },
          { value: 1, label: 'Xuất bản' }
        ]
      },
      {
        name: 'image',
        label: 'Hình ảnh',
        type: 'file',
        accept: 'image/*'
      }
    ])

    const fetchPosts = async (filters = {}) => {
      loading.value = true
      try {
        const data = await fetchList('/v1/admin/posts/list', filters)
        posts.value = data
      } catch (err) {
        console.error('Error fetching posts:', err)
      } finally {
        loading.value = false
      }
    }

    const fetchCategories = async () => {
      try {
        const data = await fetchList('/v1/admin/categories/list', { per_page: 100 })
        availableCategories.value = data.data.map(category => ({
          value: category.id,
          label: category.name,
          description: category.description
        }))
      } catch (err) {
        console.error('Error fetching categories:', err)
      }
    }

    const fetchSeries = async () => {
      try {
        const data = await fetchList('/v1/admin/series/list', { per_page: 100 })
        availableSeries.value = data.data.map(series => ({
          value: series.id,
          label: series.name,
          description: series.description
        }))
      } catch (err) {
        console.error('Error fetching series:', err)
      }
    }

    const showCreateModal = async () => {
      editingPost.value = null
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('postModal'))
      modal.show()
    }

    const showEditModal = async (post) => {
      editingPost.value = {
        ...post,
        category_id: post.category?.id || null,
        series_id: post.series?.id || null
      }
      const { Modal } = await import('bootstrap')
      const modal = new Modal(document.getElementById('postModal'))
      modal.show()
    }

    const truncateText = (text, length = 60) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    // Event handlers
    const handleFilter = (filters) => {
      fetchPosts(filters)
    }

    const handleSort = (sort) => {
      fetchPosts({ sort: sort.column, order: sort.order })
    }

    const handlePageChange = (page) => {
      fetchPosts({ page })
    }

    const handleAction = async (action, post) => {
      switch (action) {
        case 'edit':
          showEditModal(post)
          break
        case 'toggle-status':
          try {
            await toggleStatus('/v1/admin/posts', post.id, post.status ? 0 : 1)
            post.status = post.status ? 0 : 1
          } catch (err) {
            console.error('Error toggling status:', err)
          }
          break
        case 'delete':
          if (confirm(`Bạn có chắc chắn muốn xóa post "${post.title}"?`)) {
            try {
              await remove('/v1/admin/posts/delete', post.id)
              const index = posts.value.data.findIndex(p => p.id === post.id)
              if (index > -1) {
                posts.value.data.splice(index, 1)
              }
            } catch (err) {
              console.error('Error deleting post:', err)
            }
          }
          break
      }
    }

    const handleBulkAction = async (action, selectedIds) => {
      switch (action) {
        case 'bulk-publish':
          try {
            await Promise.all(selectedIds.map(id =>
              toggleStatus('/v1/admin/posts', id, 1)
            ))
            success(`Đã xuất bản ${selectedIds.length} bài viết`)
            fetchPosts()
          } catch (err) {
            console.error('Error bulk publishing:', err)
          }
          break
        case 'bulk-unpublish':
          try {
            await Promise.all(selectedIds.map(id =>
              toggleStatus('/v1/admin/posts', id, 0)
            ))
            success(`Đã ẩn ${selectedIds.length} bài viết`)
            fetchPosts()
          } catch (err) {
            console.error('Error bulk unpublishing:', err)
          }
          break
        case 'bulk-delete':
          if (confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} post đã chọn?`)) {
            try {
              await bulkDelete('/v1/admin/posts', selectedIds)
              fetchPosts()
            } catch (err) {
              console.error('Error bulk deleting:', err)
            }
          }
          break
      }
    }

    const handleSubmit = async (formData) => {
      try {
        if (editingPost.value) {
          await update('/v1/admin/posts/update', editingPost.value.id, formData)
        } else {
          await create('/v1/admin/posts/create', formData)
        }
        fetchPosts()
        return true
      } catch (err) {
        console.error('Error submitting form:', err)
        return false
      }
    }

    onMounted(() => {
      fetchPosts()
      fetchCategories()
      fetchSeries()
    })

    return {
      posts,
      loading,
      editingPost,
      columns,
      actions,
      bulkActions,
      statusOptions,
      postFields,
      showCreateModal,
      truncateText,
      handleFilter,
      handleSort,
      handlePageChange,
      handleAction,
      handleBulkAction,
      handleSubmit
    }
  }
}
</script>
