tiếp đi nhé<template>
  <div class="status-select">
    <select
      :id="id"
      v-model="selectedValue"
      class="form-select"
      :class="[
        { 'is-invalid': hasError },
        `form-select-${size}`,
        `status-select-${variant}`
      ]"
      :disabled="disabled"
      @change="handleChange"
    >
      <option value="" disabled>{{ placeholder }}</option>
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>
    
    <!-- Error message -->
    <div v-if="hasError" class="invalid-feedback">
      {{ errorMessage }}
    </div>
    
    <!-- Help text -->
    <div v-if="help" class="form-text">{{ help }}</div>
  </div>
</template>

<script>
import { ref, computed, watch } from 'vue'
import { STATUS_OPTIONS } from '../utils/statusHelper'

export default {
  name: 'StatusSelect',
  props: {
    id: {
      type: String,
      default: () => `status-select-${Math.random().toString(36).substr(2, 9)}`
    },
    label: String,
    placeholder: {
      type: String,
      default: 'Chọn trạng thái...'
    },
    modelValue: {
      type: [String, Number],
      default: ''
    },
    options: {
      type: Array,
      default: () => STATUS_OPTIONS
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    errorMessage: String,
    help: String,
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    variant: {
      type: String,
      default: 'default',
      validator: (value) => ['default', 'outline', 'filled'].includes(value)
    }
  },
  emits: ['update:modelValue', 'change'],
  setup(props, { emit }) {
    const selectedValue = ref(props.modelValue)
    
    const hasError = computed(() => !!props.errorMessage)
    
    const handleChange = () => {
      emit('update:modelValue', selectedValue.value)
      emit('change', selectedValue.value)
    }
    
    // Watch for external changes
    watch(() => props.modelValue, (newValue) => {
      selectedValue.value = newValue
    })
    
    return {
      selectedValue,
      hasError,
      handleChange
    }
  }
}
</script>

<style scoped>
.status-select {
  width: 100%;
}

.form-label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
}

.text-danger {
  color: #dc3545 !important;
}

.form-select {
  display: block;
  width: 100%;
  padding: 0.375rem 2.25rem 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #212529;
  background-color: #fff;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 16px 12px;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-select:focus {
  border-color: #86b7fe;
  outline: 0;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-select:disabled {
  background-color: #e9ecef;
  opacity: 1;
}

.form-select.is-invalid {
  border-color: #dc3545;
}

.invalid-feedback {
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875em;
  color: #dc3545;
}

.form-text {
  margin-top: 0.25rem;
  font-size: 0.875em;
  color: #6c757d;
}

/* Size variants */
.form-select-sm {
  padding: 0.25rem 1.75rem 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.form-select-lg {
  padding: 0.5rem 3rem 0.5rem 1rem;
  font-size: 1.125rem;
}

/* Style variants */
.status-select-outline {
  background-color: transparent;
  border: 2px solid #ced4da;
}

.status-select-outline:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.status-select-filled {
  background-color: #f8f9fa;
  border: 1px solid transparent;
}

.status-select-filled:focus {
  background-color: #fff;
  border-color: #86b7fe;
}
</style>
