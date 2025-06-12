<template>
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div
      v-for="toast in toasts"
      :key="toast.id"
      class="toast show"
      role="alert"
    >
      <div class="toast-header" :class="getHeaderClass(toast.type)">
        <i :class="getIcon(toast.type)" class="me-2"></i>
        <strong class="me-auto">{{ getTitle(toast.type) }}</strong>
        <small class="text-muted">{{ formatTime(toast.timestamp) }}</small>
        <button
          type="button"
          class="btn-close"
          @click="removeToast(toast.id)"
        ></button>
      </div>
      <div class="toast-body">
        {{ toast.message }}
      </div>
    </div>
  </div>
</template>

<script>
import { useToast } from '../composables/useToast'

export default {
  name: 'ToastContainer',
  setup() {
    const { toasts, removeToast } = useToast()

    const getHeaderClass = (type) => {
      switch (type) {
        case 'success':
          return 'bg-success text-white'
        case 'error':
          return 'bg-danger text-white'
        case 'warning':
          return 'bg-warning text-dark'
        default:
          return 'bg-primary text-white'
      }
    }

    const getIcon = (type) => {
      switch (type) {
        case 'success':
          return 'fas fa-check-circle'
        case 'error':
          return 'fas fa-exclamation-circle'
        case 'warning':
          return 'fas fa-exclamation-triangle'
        default:
          return 'fas fa-info-circle'
      }
    }

    const getTitle = (type) => {
      switch (type) {
        case 'success':
          return 'Thành công'
        case 'error':
          return 'Lỗi'
        case 'warning':
          return 'Cảnh báo'
        default:
          return 'Thông báo'
      }
    }

    const formatTime = (timestamp) => {
      const now = new Date()
      const diff = Math.floor((now - timestamp) / 1000)
      
      if (diff < 60) {
        return 'Vừa xong'
      } else if (diff < 3600) {
        return `${Math.floor(diff / 60)} phút trước`
      } else {
        return timestamp.toLocaleTimeString('vi-VN', {
          hour: '2-digit',
          minute: '2-digit'
        })
      }
    }

    return {
      toasts,
      removeToast,
      getHeaderClass,
      getIcon,
      getTitle,
      formatTime
    }
  }
}
</script>

<style scoped>
.toast {
  min-width: 300px;
  margin-bottom: 0.5rem;
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.toast-header {
  border-bottom: none;
}

.btn-close {
  filter: invert(1);
}

.bg-warning .btn-close {
  filter: none;
}
</style>
