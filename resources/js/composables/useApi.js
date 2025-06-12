import { ref } from 'vue'
import axios from 'axios'
import { useToast } from './useToast'

export function useApi() {
  const { success, error } = useToast()

  const loading = ref(false)
  const errors = ref({})

  const handleRequest = async (requestFn, options = {}) => {
    const {
      showSuccessToast = false,
      showErrorToast = true,
      successMessage = 'Thao tác thành công',
      loadingState = true
    } = options

    if (loadingState) {
      loading.value = true
    }

    errors.value = {}

    try {
      const response = await requestFn()

      if (response.data?.success !== false) {
        if (showSuccessToast) {
          success(response.data?.message || successMessage)
        }
        return response.data
      } else {
        throw new Error(response.data?.message || 'Có lỗi xảy ra')
      }
    } catch (err) {
      console.error('API Error:', err)

      if (err.response?.data?.errors) {
        errors.value = err.response.data.errors
      }

      const errorMessage = err.response?.data?.message || err.message || 'Có lỗi xảy ra'

      if (showErrorToast) {
        error(errorMessage)
      }

      throw err
    } finally {
      if (loadingState) {
        loading.value = false
      }
    }
  }

  // CRUD operations
  const get = (url, params = {}, options = {}) => {
    return handleRequest(() => axios.get(url, { params }), options)
  }

  const post = (url, data = {}, options = {}) => {
    return handleRequest(() => axios.post(url, data), {
      showSuccessToast: true,
      ...options
    })
  }

  const put = (url, data = {}, options = {}) => {
    return handleRequest(() => axios.put(url, data), {
      showSuccessToast: true,
      successMessage: 'Cập nhật thành công',
      ...options
    })
  }

  const patch = (url, data = {}, options = {}) => {
    return handleRequest(() => axios.patch(url, data), {
      showSuccessToast: true,
      successMessage: 'Cập nhật thành công',
      ...options
    })
  }

  const del = (url, options = {}) => {
    return handleRequest(() => axios.delete(url), {
      showSuccessToast: true,
      successMessage: 'Xóa thành công',
      ...options
    })
  }

  // Specialized methods
  const fetchList = async (endpoint, filters = {}) => {
    const params = {
      page: 1,
      per_page: 10,
      ...filters
    }

    return get(endpoint, params, { showErrorToast: true })
  }

  const fetchById = async (endpoint, id) => {
    return get(`${endpoint}/${id}`, {}, { showErrorToast: true })
  }

  const create = async (endpoint, data) => {
    return post(endpoint, data, {
      successMessage: 'Tạo mới thành công'
    })
  }

  const update = async (endpoint, id, data) => {
    return put(`${endpoint}/${id}`, data)
  }

  const remove = async (endpoint, id) => {
    return del(`${endpoint}/${id}`)
  }

  const toggleStatus = async (endpoint, id, status) => {
    return patch(`${endpoint}/status/${id}`, { status }, {
      successMessage: status ? 'Kích hoạt thành công' : 'Vô hiệu hóa thành công'
    })
  }

  const bulkDelete = async (endpoint, ids) => {
    return post(`${endpoint}/bulk-delete`, { ids }, {
      successMessage: `Đã xóa ${ids.length} mục`
    })
  }

  return {
    loading,
    errors,
    handleRequest,
    get,
    post,
    put,
    patch,
    del,
    fetchList,
    fetchById,
    create,
    update,
    remove,
    toggleStatus,
    bulkDelete
  }
}
