<template>
  <div class="vue-select" :class="{ 'is-invalid': hasError }">
    <div 
      class="select-input"
      :class="{ 
        'select-input--open': isOpen,
        'select-input--disabled': disabled,
        'is-invalid': hasError
      }"
      @click="toggleDropdown"
    >
      <!-- Selected items (multiple) -->
      <div v-if="multiple && selectedItems.length > 0" class="selected-items">
        <span 
          v-for="item in selectedItems" 
          :key="getItemValue(item)"
          class="selected-item"
        >
          {{ getItemLabel(item) }}
          <button 
            type="button" 
            class="remove-item"
            @click.stop="removeItem(item)"
            :disabled="disabled"
          >
            <i class="fas fa-times"></i>
          </button>
        </span>
      </div>

      <!-- Search input -->
      <input
        ref="searchInput"
        v-model="searchQuery"
        type="text"
        class="search-input"
        :placeholder="currentPlaceholder"
        :disabled="disabled"
        @keydown="handleKeydown"
        @focus="openDropdown"
        @blur="handleBlur"
      >

      <!-- Dropdown arrow -->
      <div class="dropdown-arrow">
        <i class="fas fa-chevron-down" :class="{ 'rotate': isOpen }"></i>
      </div>
    </div>

    <!-- Dropdown -->
    <div v-if="isOpen" class="dropdown-menu">
      <!-- Loading -->
      <div v-if="loading" class="dropdown-item loading">
        <div class="spinner-border spinner-border-sm me-2"></div>
        Đang tải...
      </div>

      <!-- No results -->
      <div v-else-if="filteredOptions.length === 0" class="dropdown-item no-results">
        {{ searchQuery ? 'Không tìm thấy kết quả' : 'Không có dữ liệu' }}
      </div>

      <!-- Options -->
      <div
        v-else
        v-for="(option, index) in filteredOptions"
        :key="getItemValue(option)"
        class="dropdown-item"
        :class="{ 
          'active': index === highlightedIndex,
          'selected': isSelected(option)
        }"
        @click="selectOption(option)"
        @mouseenter="highlightedIndex = index"
      >
        <div class="option-content">
          <span class="option-label">{{ getItemLabel(option) }}</span>
          <span v-if="getItemDescription(option)" class="option-description">
            {{ getItemDescription(option) }}
          </span>
        </div>
        <i v-if="isSelected(option)" class="fas fa-check text-success"></i>
      </div>
    </div>

    <!-- Error message -->
    <div v-if="hasError" class="invalid-feedback">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script>
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue'

export default {
  name: 'VueSelect',
  props: {
    modelValue: {
      type: [String, Number, Array, Object],
      default: null
    },
    options: {
      type: Array,
      default: () => []
    },
    multiple: {
      type: Boolean,
      default: false
    },
    placeholder: {
      type: String,
      default: 'Chọn...'
    },
    searchable: {
      type: Boolean,
      default: true
    },
    clearable: {
      type: Boolean,
      default: true
    },
    disabled: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    },
    valueKey: {
      type: String,
      default: 'value'
    },
    labelKey: {
      type: String,
      default: 'label'
    },
    descriptionKey: {
      type: String,
      default: 'description'
    },
    maxItems: {
      type: Number,
      default: 10
    },
    errorMessage: {
      type: String,
      default: ''
    }
  },
  emits: ['update:modelValue', 'search', 'open', 'close'],
  setup(props, { emit }) {
    const searchInput = ref(null)
    const isOpen = ref(false)
    const searchQuery = ref('')
    const highlightedIndex = ref(-1)

    const hasError = computed(() => !!props.errorMessage)

    const selectedItems = computed(() => {
      if (!props.modelValue) return []
      
      if (props.multiple) {
        return Array.isArray(props.modelValue) 
          ? props.options.filter(option => props.modelValue.includes(getItemValue(option)))
          : []
      } else {
        const selected = props.options.find(option => getItemValue(option) === props.modelValue)
        return selected ? [selected] : []
      }
    })

    const filteredOptions = computed(() => {
      let filtered = props.options

      if (searchQuery.value && props.searchable) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(option => 
          getItemLabel(option).toLowerCase().includes(query) ||
          (getItemDescription(option) && getItemDescription(option).toLowerCase().includes(query))
        )
      }

      return filtered.slice(0, props.maxItems)
    })

    const currentPlaceholder = computed(() => {
      if (props.multiple && selectedItems.value.length > 0) {
        return `${selectedItems.value.length} mục đã chọn`
      }
      if (!props.multiple && selectedItems.value.length > 0) {
        return getItemLabel(selectedItems.value[0])
      }
      return props.placeholder
    })

    const getItemValue = (item) => {
      return typeof item === 'object' ? item[props.valueKey] : item
    }

    const getItemLabel = (item) => {
      return typeof item === 'object' ? item[props.labelKey] : item
    }

    const getItemDescription = (item) => {
      return typeof item === 'object' ? item[props.descriptionKey] : null
    }

    const isSelected = (option) => {
      const value = getItemValue(option)
      if (props.multiple) {
        return Array.isArray(props.modelValue) && props.modelValue.includes(value)
      }
      return props.modelValue === value
    }

    const openDropdown = () => {
      if (props.disabled) return
      isOpen.value = true
      highlightedIndex.value = -1
      emit('open')
    }

    const closeDropdown = () => {
      isOpen.value = false
      searchQuery.value = ''
      highlightedIndex.value = -1
      emit('close')
    }

    const toggleDropdown = () => {
      if (isOpen.value) {
        closeDropdown()
      } else {
        openDropdown()
        nextTick(() => {
          if (searchInput.value) {
            searchInput.value.focus()
          }
        })
      }
    }

    const selectOption = (option) => {
      const value = getItemValue(option)
      
      if (props.multiple) {
        const currentValue = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        const index = currentValue.indexOf(value)
        
        if (index > -1) {
          currentValue.splice(index, 1)
        } else {
          currentValue.push(value)
        }
        
        emit('update:modelValue', currentValue)
      } else {
        emit('update:modelValue', value)
        closeDropdown()
      }
    }

    const removeItem = (item) => {
      if (props.disabled) return
      
      const value = getItemValue(item)
      
      if (props.multiple) {
        const currentValue = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        const index = currentValue.indexOf(value)
        if (index > -1) {
          currentValue.splice(index, 1)
          emit('update:modelValue', currentValue)
        }
      } else {
        emit('update:modelValue', null)
      }
    }

    const handleKeydown = (event) => {
      switch (event.key) {
        case 'ArrowDown':
          event.preventDefault()
          if (!isOpen.value) {
            openDropdown()
          } else {
            highlightedIndex.value = Math.min(highlightedIndex.value + 1, filteredOptions.value.length - 1)
          }
          break
        case 'ArrowUp':
          event.preventDefault()
          highlightedIndex.value = Math.max(highlightedIndex.value - 1, -1)
          break
        case 'Enter':
          event.preventDefault()
          if (isOpen.value && highlightedIndex.value >= 0) {
            selectOption(filteredOptions.value[highlightedIndex.value])
          } else if (!isOpen.value) {
            openDropdown()
          }
          break
        case 'Escape':
          event.preventDefault()
          closeDropdown()
          break
      }
    }

    const handleBlur = () => {
      // Delay closing to allow click events on dropdown items
      setTimeout(() => {
        closeDropdown()
      }, 150)
    }

    const handleClickOutside = (event) => {
      if (!event.target.closest('.vue-select')) {
        closeDropdown()
      }
    }

    // Watch search query for external search
    watch(searchQuery, (newQuery) => {
      if (props.searchable) {
        emit('search', newQuery)
      }
    })

    onMounted(() => {
      document.addEventListener('click', handleClickOutside)
    })

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside)
    })

    return {
      searchInput,
      isOpen,
      searchQuery,
      highlightedIndex,
      hasError,
      selectedItems,
      filteredOptions,
      currentPlaceholder,
      getItemValue,
      getItemLabel,
      getItemDescription,
      isSelected,
      openDropdown,
      closeDropdown,
      toggleDropdown,
      selectOption,
      removeItem,
      handleKeydown,
      handleBlur
    }
  }
}
</script>

<style scoped>
.vue-select {
  position: relative;
  width: 100%;
}

.select-input {
  display: flex;
  align-items: center;
  min-height: 38px;
  padding: 6px 12px;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
  background-color: #fff;
  cursor: pointer;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.select-input:hover {
  border-color: #86b7fe;
}

.select-input--open {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.select-input--disabled {
  background-color: #e9ecef;
  cursor: not-allowed;
}

.select-input.is-invalid {
  border-color: #dc3545;
}

.selected-items {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
  flex: 1;
}

.selected-item {
  display: inline-flex;
  align-items: center;
  padding: 2px 8px;
  background-color: #e9ecef;
  border-radius: 0.25rem;
  font-size: 0.875rem;
  gap: 4px;
}

.remove-item {
  background: none;
  border: none;
  color: #6c757d;
  cursor: pointer;
  padding: 0;
  font-size: 0.75rem;
}

.remove-item:hover {
  color: #dc3545;
}

.search-input {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  min-width: 60px;
}

.dropdown-arrow {
  margin-left: 8px;
  color: #6c757d;
  transition: transform 0.2s ease;
}

.dropdown-arrow .rotate {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 1000;
  max-height: 200px;
  overflow-y: auto;
  background-color: #fff;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  margin-top: 2px;
}

.dropdown-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 12px;
  cursor: pointer;
  border-bottom: 1px solid #f8f9fa;
}

.dropdown-item:last-child {
  border-bottom: none;
}

.dropdown-item:hover,
.dropdown-item.active {
  background-color: #f8f9fa;
}

.dropdown-item.selected {
  background-color: #e7f3ff;
}

.dropdown-item.loading,
.dropdown-item.no-results {
  cursor: default;
  color: #6c757d;
  font-style: italic;
}

.option-content {
  flex: 1;
}

.option-label {
  display: block;
  font-weight: 500;
}

.option-description {
  display: block;
  font-size: 0.875rem;
  color: #6c757d;
}

.invalid-feedback {
  display: block;
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: #dc3545;
}
</style>
