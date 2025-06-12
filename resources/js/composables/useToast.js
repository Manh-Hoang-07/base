import { ref } from 'vue'

// Global toast state
const toasts = ref([])
let toastId = 0

export function useToast() {
  const addToast = (message, type = 'info', duration = 5000) => {
    const toast = {
      id: ++toastId,
      message,
      type,
      timestamp: new Date()
    }

    toasts.value.push(toast)

    // Auto remove after duration
    if (duration > 0) {
      setTimeout(() => {
        removeToast(toast.id)
      }, duration)
    }

    return toast.id
  }

  const removeToast = (id) => {
    const index = toasts.value.findIndex(toast => toast.id === id)
    if (index > -1) {
      toasts.value.splice(index, 1)
    }
  }

  const success = (message, duration = 5000) => {
    return addToast(message, 'success', duration)
  }

  const error = (message, duration = 8000) => {
    return addToast(message, 'error', duration)
  }

  const warning = (message, duration = 6000) => {
    return addToast(message, 'warning', duration)
  }

  const info = (message, duration = 5000) => {
    return addToast(message, 'info', duration)
  }

  const clear = () => {
    toasts.value = []
  }

  return {
    toasts,
    addToast,
    removeToast,
    success,
    error,
    warning,
    info,
    clear
  }
}
