<template>
  <div class="mb-3">
    <label v-if="label" :for="fieldId" class="form-label">
      {{ label }}
      <span v-if="required" class="text-danger">*</span>
    </label>
    <select
      :id="fieldId"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="form-select"
      :class="{ 'is-invalid': hasError }"
      :required="required"
      :disabled="disabled || loading"
      :multiple="multiple"
    >
      <option v-if="placeholder && !multiple" value="" disabled>{{ placeholder }}</option>
      <option
        v-for="option in computedOptions"
        :key="option.value"
        :value="option.value"
        :selected="isSelected(option.value)"
      >
        {{ option.label }}
      </option>
    </select>
    <div v-if="help" class="form-text">{{ help }}</div>
    <div v-if="hasError" class="invalid-feedback">{{ error }}</div>
    <div v-if="loading" class="form-text">
      <i class="fas fa-spinner fa-spin me-1"></i>Đang tải...
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

export default {
  name: 'SelectField',
  emits: ['update:modelValue'],
  props: {
    modelValue: {
      type: [String, Number, Array],
      default: null
    },
    label: {
      type: String,
      default: null
    },
    placeholder: {
      type: String,
      default: 'Chọn...'
    },
    help: {
      type: String,
      default: null
    },
    error: {
      type: String,
      default: null
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    multiple: {
      type: Boolean,
      default: false
    },
    id: {
      type: String,
      default: null
    },
    // Static options
    options: {
      type: Array,
      default: () => []
    },
    // API options
    apiUrl: {
      type: String,
      default: null
    },
    valueKey: {
      type: String,
      default: 'id'
    },
    labelKey: {
      type: String,
      default: 'name'
    },
    // Auto load on mount
    autoLoad: {
      type: Boolean,
      default: true
    }
  },
  setup(props, { emit }) {
    const fieldId = computed(() => {
      return props.id || `select-field-${Math.random().toString(36).substr(2, 9)}`
    })

    const hasError = computed(() => {
      return !!props.error
    })

    const loading = ref(false)
    const apiOptions = ref([])

    const computedOptions = computed(() => {
      if (props.options.length > 0) {
        // Use static options
        return props.options.map(option => {
          if (typeof option === 'object' && option.value !== undefined && option.label !== undefined) {
            return option
          }
          return {
            value: option[props.valueKey] || option.id || option.value,
            label: option[props.labelKey] || option.name || option.label || option.title
          }
        })
      } else if (props.apiUrl) {
        // Use API options
        return apiOptions.value.map(option => ({
          value: option[props.valueKey],
          label: option[props.labelKey]
        }))
      }
      return []
    })

    const isSelected = (value) => {
      if (props.multiple) {
        return Array.isArray(props.modelValue) && props.modelValue.includes(value)
      }
      return props.modelValue == value
    }

    const loadOptions = async () => {
      if (!props.apiUrl) return

      loading.value = true
      try {
        const response = await axios.get(props.apiUrl)

        if (response.data.success && response.data.data) {
          apiOptions.value = response.data.data
        } else {
          apiOptions.value = []
        }
      } catch (error) {
        console.error('Error loading options:', error)
        apiOptions.value = []
      } finally {
        loading.value = false
      }
    }

    // Watch for API URL changes
    watch(() => props.apiUrl, () => {
      if (props.apiUrl && props.autoLoad) {
        loadOptions()
      }
    })

    onMounted(() => {
      if (props.apiUrl && props.autoLoad) {
        loadOptions()
      }
    })

    return {
      fieldId,
      hasError,
      loading,
      computedOptions,
      isSelected,
      loadOptions
    }
  }
}
</script>

<style scoped>
.form-select:focus {
  border-color: #86b7fe;
  outline: 0;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.is-invalid {
  border-color: #dc3545;
}

.is-invalid:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.fa-spinner {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
