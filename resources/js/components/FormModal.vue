<template>
  <div class="modal fade" :id="modalId" tabindex="-1" ref="modalElement">
    <div class="modal-dialog" :class="modalSize">
      <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title">
            <i v-if="icon" :class="icon" class="me-2"></i>
            {{ title }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <form @submit.prevent="handleSubmit">
            <slot name="form" :form="form" :errors="errors" :loading="loading">
              <!-- Default form content -->
              <div v-for="field in fields" :key="field.name" class="mb-3">
                <label :for="field.name" class="form-label">
                  {{ field.label }}
                  <span v-if="field.required" class="text-danger">*</span>
                </label>

                <!-- Text Input -->
                <input
                  v-if="field.type === 'text' || field.type === 'email' || field.type === 'password'"
                  :id="field.name"
                  v-model="form[field.name]"
                  :type="field.type"
                  class="form-control"
                  :class="{ 'is-invalid': errors[field.name] }"
                  :placeholder="field.placeholder"
                  :required="field.required"
                >

                <!-- Textarea -->
                <textarea
                  v-else-if="field.type === 'textarea'"
                  :id="field.name"
                  v-model="form[field.name]"
                  class="form-control"
                  :class="{ 'is-invalid': errors[field.name] }"
                  :placeholder="field.placeholder"
                  :rows="field.rows || 3"
                  :required="field.required"
                ></textarea>

                <!-- Select -->
                <VueSelect
                  v-else-if="field.type === 'select'"
                  :id="field.name"
                  v-model="form[field.name]"
                  :options="field.options"
                  :multiple="field.multiple"
                  :placeholder="field.placeholder || 'Chọn...'"
                  :error-message="errors[field.name]"
                  :searchable="field.searchable !== false"
                  :clearable="field.clearable !== false"
                />

                <!-- Checkbox -->
                <div v-else-if="field.type === 'checkbox'" class="form-check">
                  <input
                    :id="field.name"
                    v-model="form[field.name]"
                    type="checkbox"
                    class="form-check-input"
                    :class="{ 'is-invalid': errors[field.name] }"
                  >
                  <label :for="field.name" class="form-check-label">
                    {{ field.checkboxLabel || field.label }}
                  </label>
                </div>

                <!-- File Upload -->
                <input
                  v-else-if="field.type === 'file'"
                  :id="field.name"
                  @change="handleFileChange($event, field.name)"
                  type="file"
                  class="form-control"
                  :class="{ 'is-invalid': errors[field.name] }"
                  :accept="field.accept"
                  :multiple="field.multiple"
                >

                <!-- Help text -->
                <div v-if="field.help" class="form-text">{{ field.help }}</div>

                <!-- Error message -->
                <div v-if="errors[field.name]" class="invalid-feedback">
                  {{ errors[field.name] }}
                </div>
              </div>
            </slot>

            <!-- Global error message -->
            <div v-if="errorMessage" class="alert alert-danger">
              <i class="fas fa-exclamation-triangle me-2"></i>
              {{ errorMessage }}
            </div>

            <!-- Success message -->
            <div v-if="successMessage" class="alert alert-success">
              <i class="fas fa-check-circle me-2"></i>
              {{ successMessage }}
            </div>
          </form>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <slot name="footer" :loading="loading" :handleSubmit="handleSubmit">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Hủy
            </button>
            <button
              type="button"
              @click="handleSubmit"
              class="btn btn-primary"
              :disabled="loading"
            >
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              <i v-else :class="submitIcon" class="me-2"></i>
              {{ loading ? 'Đang xử lý...' : submitText }}
            </button>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, watch, nextTick, onMounted, onUnmounted } from 'vue'
import VueSelect from './VueSelect.vue'

export default {
  name: 'FormModal',
  components: {
    VueSelect
  },
  props: {
    modalId: {
      type: String,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      default: ''
    },
    fields: {
      type: Array,
      default: () => []
    },
    initialData: {
      type: Object,
      default: () => ({})
    },
    submitText: {
      type: String,
      default: 'Lưu'
    },
    submitIcon: {
      type: String,
      default: 'fas fa-save'
    },
    modalSize: {
      type: String,
      default: 'modal-lg'
    },
    resetOnClose: {
      type: Boolean,
      default: true
    }
  },
  emits: ['submit', 'show', 'hide'],
  setup(props, { emit }) {
    const modalElement = ref(null)
    const form = reactive({})
    const errors = ref({})
    const errorMessage = ref('')
    const successMessage = ref('')
    const loading = ref(false)
    let modalInstance = null

    // Initialize form data
    const initializeForm = () => {
      // Reset form
      Object.keys(form).forEach(key => {
        delete form[key]
      })

      // Set default values from fields
      props.fields.forEach(field => {
        if (field.type === 'checkbox') {
          form[field.name] = false
        } else if (field.type === 'select' && field.multiple) {
          form[field.name] = []
        } else {
          form[field.name] = ''
        }
      })

      // Apply initial data
      Object.assign(form, props.initialData)
    }

    const handleSubmit = async () => {
      errors.value = {}
      errorMessage.value = ''
      successMessage.value = ''

      // Basic validation
      const validationErrors = {}
      props.fields.forEach(field => {
        if (field.required) {
          const value = form[field.name]
          if (!value || (Array.isArray(value) && value.length === 0)) {
            validationErrors[field.name] = `${field.label} là bắt buộc`
          }
        }
      })

      if (Object.keys(validationErrors).length > 0) {
        errors.value = validationErrors
        return
      }

      loading.value = true

      try {
        const result = await emit('submit', { ...form })

        if (result !== false) {
          successMessage.value = 'Thao tác thành công'

          // Close modal after success
          setTimeout(() => {
            hide()
          }, 1000)
        }
      } catch (error) {
        if (error.response?.data?.errors) {
          errors.value = error.response.data.errors
        } else {
          errorMessage.value = error.response?.data?.message || 'Có lỗi xảy ra'
        }
      } finally {
        loading.value = false
      }
    }

    const handleFileChange = (event, fieldName) => {
      const files = event.target.files
      if (files.length > 0) {
        form[fieldName] = files.length === 1 ? files[0] : Array.from(files)
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

    const reset = () => {
      initializeForm()
      errors.value = {}
      errorMessage.value = ''
      successMessage.value = ''
      loading.value = false
    }

    // Watch for initialData changes
    watch(() => props.initialData, () => {
      initializeForm()
    }, { deep: true })

    onMounted(async () => {
      await nextTick()

      if (modalElement.value) {
        // Initialize Bootstrap modal
        const { Modal } = await import('bootstrap')
        modalInstance = new Modal(modalElement.value)

        // Add event listeners
        modalElement.value.addEventListener('show.bs.modal', () => {
          emit('show')
          initializeForm()
        })

        modalElement.value.addEventListener('hide.bs.modal', () => {
          emit('hide')
          if (props.resetOnClose) {
            reset()
          }
        })
      }
    })

    onUnmounted(() => {
      if (modalInstance) {
        modalInstance.dispose()
      }
    })

    // Initialize form on setup
    initializeForm()

    return {
      modalElement,
      form,
      errors,
      errorMessage,
      successMessage,
      loading,
      handleSubmit,
      handleFileChange,
      show,
      hide,
      reset
    }
  }
}
</script>

<style scoped>
.modal-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
}

.modal-title {
  font-weight: 600;
  color: #495057;
}

.form-label {
  font-weight: 500;
  color: #495057;
}

.text-danger {
  color: #dc3545 !important;
}

.alert {
  border: none;
  border-radius: 0.5rem;
}
</style>
