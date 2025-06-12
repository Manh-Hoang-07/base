<template>
  <div class="modal fade" :id="modalId" tabindex="-1" ref="modalElement">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <!-- Header -->
        <div class="modal-header" :class="headerClass">
          <h5 class="modal-title d-flex align-items-center">
            <i :class="iconClass" class="me-2"></i>
            {{ title }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <div class="d-flex align-items-start">
            <div class="flex-shrink-0 me-3">
              <div class="confirm-icon" :class="iconBgClass">
                <i :class="iconClass"></i>
              </div>
            </div>
            <div class="flex-grow-1">
              <p class="mb-2 fw-semibold">{{ message }}</p>
              <p v-if="description" class="text-muted mb-0 small">
                {{ description }}
              </p>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button 
            type="button" 
            class="btn btn-secondary" 
            data-bs-dismiss="modal"
            :disabled="loading"
          >
            {{ cancelText }}
          </button>
          <button 
            type="button" 
            @click="handleConfirm"
            :class="confirmButtonClass"
            :disabled="loading"
          >
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            <i v-else :class="confirmIconClass" class="me-2"></i>
            {{ loading ? loadingText : confirmText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, nextTick, onMounted, onUnmounted } from 'vue'

export default {
  name: 'ConfirmDialog',
  props: {
    modalId: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'warning',
      validator: (value) => ['success', 'warning', 'danger', 'info'].includes(value)
    },
    title: {
      type: String,
      default: 'Xác nhận'
    },
    message: {
      type: String,
      required: true
    },
    description: {
      type: String,
      default: ''
    },
    confirmText: {
      type: String,
      default: 'Xác nhận'
    },
    cancelText: {
      type: String,
      default: 'Hủy'
    },
    loadingText: {
      type: String,
      default: 'Đang xử lý...'
    }
  },
  emits: ['confirm', 'cancel', 'show', 'hide'],
  setup(props, { emit }) {
    const modalElement = ref(null)
    const loading = ref(false)
    let modalInstance = null

    const headerClass = computed(() => {
      switch (props.type) {
        case 'success':
          return 'bg-success text-white'
        case 'danger':
          return 'bg-danger text-white'
        case 'warning':
          return 'bg-warning text-dark'
        default:
          return 'bg-info text-white'
      }
    })

    const iconClass = computed(() => {
      switch (props.type) {
        case 'success':
          return 'fas fa-check-circle'
        case 'danger':
          return 'fas fa-exclamation-triangle'
        case 'warning':
          return 'fas fa-exclamation-triangle'
        default:
          return 'fas fa-info-circle'
      }
    })

    const iconBgClass = computed(() => {
      switch (props.type) {
        case 'success':
          return 'bg-success'
        case 'danger':
          return 'bg-danger'
        case 'warning':
          return 'bg-warning'
        default:
          return 'bg-info'
      }
    })

    const confirmButtonClass = computed(() => {
      switch (props.type) {
        case 'success':
          return 'btn btn-success'
        case 'danger':
          return 'btn btn-danger'
        case 'warning':
          return 'btn btn-warning'
        default:
          return 'btn btn-info'
      }
    })

    const confirmIconClass = computed(() => {
      switch (props.type) {
        case 'success':
          return 'fas fa-check'
        case 'danger':
          return 'fas fa-trash'
        case 'warning':
          return 'fas fa-exclamation-triangle'
        default:
          return 'fas fa-check'
      }
    })

    const handleConfirm = async () => {
      loading.value = true
      
      try {
        const result = await emit('confirm')
        
        if (result !== false) {
          hide()
        }
      } catch (error) {
        console.error('Confirm action error:', error)
      } finally {
        loading.value = false
      }
    }

    const show = () => {
      if (modalInstance) {
        modalInstance.show()
      }
    }

    const hide = () => {
      if (modalInstance) {
        modalInstance.hide()
      }
    }

    onMounted(async () => {
      await nextTick()
      
      if (modalElement.value) {
        // Initialize Bootstrap modal
        const { Modal } = await import('bootstrap')
        modalInstance = new Modal(modalElement.value)

        // Add event listeners
        modalElement.value.addEventListener('show.bs.modal', () => {
          emit('show')
          loading.value = false
        })

        modalElement.value.addEventListener('hide.bs.modal', () => {
          emit('hide')
          loading.value = false
        })

        modalElement.value.addEventListener('hidden.bs.modal', () => {
          emit('cancel')
        })
      }
    })

    onUnmounted(() => {
      if (modalInstance) {
        modalInstance.dispose()
      }
    })

    return {
      modalElement,
      loading,
      headerClass,
      iconClass,
      iconBgClass,
      confirmButtonClass,
      confirmIconClass,
      handleConfirm,
      show,
      hide
    }
  }
}
</script>

<style scoped>
.modal-header {
  border-bottom: none;
}

.modal-footer {
  border-top: 1px solid #dee2e6;
}

.confirm-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.25rem;
}

.bg-warning .confirm-icon {
  color: #212529;
}

.btn-close {
  filter: invert(1);
}

.bg-warning .btn-close,
.bg-light .btn-close {
  filter: none;
}

.fw-semibold {
  font-weight: 600;
}

/* Animation */
.modal.fade .modal-dialog {
  transition: transform 0.3s ease-out;
  transform: translate(0, -50px);
}

.modal.show .modal-dialog {
  transform: none;
}

/* Focus styles */
.btn:focus {
  box-shadow: 0 0 0 0.25rem rgba(var(--bs-btn-focus-shadow-rgb), 0.5);
}

/* Loading state */
.btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}
</style>
