<template>
  <div class="base-select">
    <select
      :id="id"
      v-model="selectedValue"
      class="form-select"
      :class="[
        { 'is-invalid': hasError },
        `form-select-${size}`,
        `base-select-${variant}`
      ]"
      :disabled="disabled"
      :required="required"
      @change="handleChange"
      @focus="$emit('focus')"
      @blur="$emit('blur')"
    >
      <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
      <option
        v-for="option in normalizedOptions"
        :key="option.value"
        :value="option.value"
        :disabled="option.disabled"
      >
        {{ option.label }}
      </option>
    </select>
  </div>
</template>

<script>
import { ref, computed, watch } from 'vue'

export default {
  name: 'BaseSelect',
  props: {
    id: {
      type: String,
      default: () => `base-select-${Math.random().toString(36).substr(2, 9)}`
    },
    placeholder: {
      type: String,
      default: 'Chọn...'
    },
    modelValue: {
      type: [String, Number, Boolean],
      default: ''
    },
    options: {
      type: Array,
      required: true
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
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    variant: {
      type: String,
      default: 'default',
      validator: (value) => ['default', 'outline', 'filled'].includes(value)
    },
    valueKey: {
      type: String,
      default: 'value'
    },
    labelKey: {
      type: String,
      default: 'label'
    }
  },
  emits: ['update:modelValue', 'change', 'focus', 'blur'],
  setup(props, { emit }) {
    const selectedValue = ref(props.modelValue)
    
    const hasError = computed(() => !!props.errorMessage)
    
    // Normalize options to consistent format
    const normalizedOptions = computed(() => {
      return props.options.map(option => {
        if (typeof option === 'string' || typeof option === 'number') {
          return {
            value: option,
            label: option.toString(),
            disabled: false
          }
        }
        
        return {
          value: option[props.valueKey] ?? option.value,
          label: option[props.labelKey] ?? option.label ?? option.name ?? option.title,
          disabled: option.disabled ?? false
        }
      })
    })
    
    const handleChange = () => {
      // Convert to appropriate type
      let value = selectedValue.value
      
      // Try to convert to number if original was number
      if (typeof props.modelValue === 'number' && value !== '') {
        const numValue = Number(value)
        if (!isNaN(numValue)) {
          value = numValue
        }
      }
      
      // Convert to boolean if original was boolean
      if (typeof props.modelValue === 'boolean') {
        value = value === 'true' || value === true || value === 1 || value === '1'
      }
      
      emit('update:modelValue', value)
      emit('change', value)
    }
    
    // Watch for external changes
    watch(() => props.modelValue, (newValue) => {
      selectedValue.value = newValue
    })
    
    return {
      selectedValue,
      hasError,
      normalizedOptions,
      handleChange
    }
  }
}
</script>

<style scoped>
.base-select {
  width: 100%;
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
.base-select-outline {
  background-color: transparent;
  border: 2px solid #ced4da;
}

.base-select-outline:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.base-select-filled {
  background-color: #f8f9fa;
  border: 1px solid transparent;
}

.base-select-filled:focus {
  background-color: #fff;
  border-color: #86b7fe;
}
</style>
