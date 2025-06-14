<template>
  <div class="universal-select" :class="{ 'mb-3': showLabel }">
    <!-- Label -->
    <label v-if="label" :for="fieldId" class="form-label">
      {{ label }}
      <span v-if="required" class="text-danger">*</span>
    </label>

    <!-- Simple Select Mode -->
    <select
      v-if="mode === 'simple'"
      :id="fieldId"
      v-model="selectedValue"
      class="form-select"
      :class="[
        { 'is-invalid': hasError },
        `form-select-${size}`,
        variant !== 'default' ? `select-${variant}` : ''
      ]"
      :disabled="disabled || loading"
      :required="required"
      :multiple="multiple"
      @change="handleSimpleChange"
      @focus="$emit('focus')"
      @blur="$emit('blur')"
    >
      <option v-if="placeholder && !multiple" value="" disabled>{{ placeholder }}</option>
      <option
        v-for="option in normalizedOptions"
        :key="option.value"
        :value="option.value"
        :disabled="option.disabled"
        :selected="isOptionSelected(option.value)"
      >
        {{ option.label }}
      </option>
    </select>

    <!-- Advanced Select Mode (with search, API, etc.) -->
    <div v-else class="select-container" :class="{ 'is-invalid': hasError }">
      <div 
        class="select-input"
        :class="{ 
          'select-input--open': isOpen,
          'select-input--disabled': disabled,
          'is-invalid': hasError
        }"
        @click="toggleDropdown"
      >
        <!-- Selected items display -->
        <div class="selected-items">
          <!-- Multiple selection -->
          <template v-if="multiple">
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
          </template>
          
          <!-- Single selection -->
          <template v-else>
            <span v-if="selectedItems.length > 0" class="selected-single">
              {{ getItemLabel(selectedItems[0]) }}
            </span>
          </template>
        </div>

        <!-- Search input -->
        <input
          v-if="searchable"
          ref="searchInput"
          v-model="searchQuery"
          type="text"
          class="search-input"
          :placeholder="currentPlaceholder"
          :disabled="disabled"
          @keydown="handleKeydown"
          @focus="openDropdown"
          @input="handleSearch"
        />

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
          v-for="(option, index) in paginatedOptions"
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
            <span v-if="getItemDescription && getItemDescription(option)" class="option-description">
              {{ getItemDescription(option) }}
            </span>
          </div>
          <i v-if="isSelected(option)" class="fas fa-check text-success"></i>
        </div>
        
        <!-- Load more button -->
        <div 
          v-if="hasMore && !loading && apiUrl" 
          class="dropdown-item load-more"
          @click="loadMore"
        >
          <i class="fas fa-plus"></i> Tải thêm
        </div>
      </div>
    </div>

    <!-- Help text -->
    <div v-if="help" class="form-text">{{ help }}</div>
    
    <!-- Error message -->
    <div v-if="hasError" class="invalid-feedback" :class="{ 'd-block': mode !== 'simple' }">
      {{ errorMessage || error }}
    </div>
    
    <!-- Loading indicator for API mode -->
    <div v-if="loading && apiUrl" class="form-text">
      <i class="fas fa-spinner fa-spin me-1"></i>Đang tải...
    </div>
  </div>
</template>

<script>
import { ref, reactive, computed, watch, nextTick, onMounted, onUnmounted } from 'vue'
import { useApi } from '../composables/useApi'

export default {
  name: 'UniversalSelect',
  props: {
    // Basic props
    id: {
      type: String,
      default: () => `universal-select-${Math.random().toString(36).substr(2, 9)}`
    },
    label: String,
    placeholder: {
      type: String,
      default: 'Chọn...'
    },
    modelValue: {
      type: [String, Number, Array, Boolean],
      default: null
    },
    multiple: {
      type: Boolean,
      default: false
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    help: String,
    error: String,
    errorMessage: String,
    
    // Mode selection
    mode: {
      type: String,
      default: 'simple',
      validator: (value) => ['simple', 'advanced'].includes(value)
    },
    
    // Simple mode props
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
    
    // Advanced mode props
    searchable: {
      type: Boolean,
      default: true
    },
    clearable: {
      type: Boolean,
      default: true
    },
    
    // Options
    options: {
      type: Array,
      default: () => []
    },
    
    // API props
    apiUrl: String,
    searchParam: {
      type: String,
      default: 'search'
    },
    pageParam: {
      type: String,
      default: 'page'
    },
    limitParam: {
      type: String,
      default: 'limit'
    },
    limit: {
      type: Number,
      default: 10
    },
    preloadData: {
      type: Array,
      default: () => []
    },
    autoLoad: {
      type: Boolean,
      default: true
    },
    
    // Field mapping
    valueKey: {
      type: String,
      default: 'id'
    },
    labelKey: {
      type: String,
      default: 'name'
    },
    descriptionKey: {
      type: String,
      default: 'description'
    },
    
    // Display
    showLabel: {
      type: Boolean,
      default: true
    },
    maxItems: {
      type: Number,
      default: 10
    }
  },
  emits: ['update:modelValue', 'change', 'search', 'focus', 'blur', 'open', 'close'],
  setup(props, { emit }) {
    const { fetchList } = useApi()
    
    // Reactive data
    const selectedValue = ref(props.modelValue)
    const isOpen = ref(false)
    const loading = ref(false)
    const searchQuery = ref('')
    const apiOptions = ref([...props.preloadData])
    const currentPage = ref(1)
    const hasMore = ref(true)
    const highlightedIndex = ref(-1)
    
    // Refs
    const searchInput = ref(null)
    
    // Computed
    const fieldId = computed(() => props.id)
    const hasError = computed(() => !!(props.errorMessage || props.error))
    
    // Computed properties
    const normalizedOptions = computed(() => {
      const sourceOptions = props.apiUrl ? apiOptions.value : props.options

      return sourceOptions.map(option => {
        if (typeof option === 'string' || typeof option === 'number') {
          return {
            value: option,
            label: option.toString(),
            disabled: false
          }
        }

        return {
          value: option[props.valueKey] ?? option.value ?? option.id,
          label: option[props.labelKey] ?? option.label ?? option.name ?? option.title,
          description: option[props.descriptionKey] ?? option.description,
          disabled: option.disabled ?? false
        }
      })
    })

    const selectedItems = computed(() => {
      if (!props.modelValue) return []

      const values = Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue]
      return normalizedOptions.value.filter(option => values.includes(option.value))
    })

    const filteredOptions = computed(() => {
      let filtered = normalizedOptions.value

      if (searchQuery.value && props.mode === 'advanced') {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(option =>
          option.label.toLowerCase().includes(query) ||
          (option.description && option.description.toLowerCase().includes(query))
        )
      }

      return filtered
    })

    const paginatedOptions = computed(() => {
      if (props.apiUrl) {
        return filteredOptions.value
      }
      return filteredOptions.value.slice(0, props.maxItems)
    })

    const currentPlaceholder = computed(() => {
      if (props.multiple && selectedItems.value.length > 0) {
        return `${selectedItems.value.length} mục đã chọn`
      }
      if (!props.multiple && selectedItems.value.length > 0 && !props.searchable) {
        return selectedItems.value[0].label
      }
      return props.placeholder
    })

    // Helper methods
    const getItemValue = (item) => {
      return typeof item === 'object' ? item.value : item
    }

    const getItemLabel = (item) => {
      return typeof item === 'object' ? item.label : item
    }

    const getItemDescription = (item) => {
      return typeof item === 'object' ? item.description : null
    }

    const isOptionSelected = (value) => {
      if (props.multiple) {
        return Array.isArray(props.modelValue) && props.modelValue.includes(value)
      }
      return props.modelValue == value
    }

    const isSelected = (option) => {
      return isOptionSelected(getItemValue(option))
    }

    return {
      selectedValue,
      isOpen,
      loading,
      searchQuery,
      apiOptions,
      currentPage,
      hasMore,
      highlightedIndex,
      searchInput,
      fieldId,
      hasError,
      normalizedOptions,
      selectedItems,
      filteredOptions,
      paginatedOptions,
      currentPlaceholder,
      getItemValue,
      getItemLabel,
      getItemDescription,
      isOptionSelected,
      isSelected,
      // Methods
      handleSimpleChange,
      toggleDropdown,
      openDropdown,
      selectOption,
      removeItem,
      handleKeydown,
      handleSearch,
      loadMore
    }

    // Methods
    function handleSimpleChange() {
      let value = selectedValue.value

      // Type conversion
      if (typeof props.modelValue === 'number' && value !== '') {
        const numValue = Number(value)
        if (!isNaN(numValue)) {
          value = numValue
        }
      }

      if (typeof props.modelValue === 'boolean') {
        value = value === 'true' || value === true || value === 1 || value === '1'
      }

      emit('update:modelValue', value)
      emit('change', value)
    }

    function toggleDropdown() {
      if (props.disabled) return

      if (isOpen.value) {
        closeDropdown()
      } else {
        openDropdown()
      }
    }

    function openDropdown() {
      if (props.disabled) return

      isOpen.value = true
      highlightedIndex.value = -1
      emit('open')

      nextTick(() => {
        if (searchInput.value) {
          searchInput.value.focus()
        }
      })
    }

    function closeDropdown() {
      isOpen.value = false
      searchQuery.value = ''
      highlightedIndex.value = -1
      emit('close')
    }

    function selectOption(option) {
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
        emit('change', currentValue)
      } else {
        emit('update:modelValue', value)
        emit('change', value)
        closeDropdown()
      }
    }

    function removeItem(item) {
      if (props.disabled) return

      const value = getItemValue(item)

      if (props.multiple) {
        const currentValue = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        const index = currentValue.indexOf(value)
        if (index > -1) {
          currentValue.splice(index, 1)
          emit('update:modelValue', currentValue)
          emit('change', currentValue)
        }
      } else {
        emit('update:modelValue', null)
        emit('change', null)
      }
    }

    function handleKeydown(event) {
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

    async function handleSearch() {
      if (props.apiUrl) {
        currentPage.value = 1
        hasMore.value = true
        await loadData(searchQuery.value, 1, false)
      }

      highlightedIndex.value = -1
      emit('search', searchQuery.value)
    }

    async function loadMore() {
      if (hasMore.value && !loading.value && props.apiUrl) {
        await loadData(searchQuery.value, currentPage.value + 1, true)
      }
    }

    async function loadData(search = '', page = 1, append = false) {
      if (loading.value || !props.apiUrl) return

      loading.value = true
      try {
        const params = {
          [props.searchParam]: search,
          [props.pageParam]: page,
          [props.limitParam]: props.limit
        }

        const response = await fetchList(props.apiUrl, params)
        const newOptions = response.data || []

        if (append) {
          apiOptions.value = [...apiOptions.value, ...newOptions]
        } else {
          apiOptions.value = newOptions
        }

        hasMore.value = newOptions.length === props.limit
        currentPage.value = page
      } catch (error) {
        console.error('Error loading select data:', error)
      } finally {
        loading.value = false
      }
    }

    // Event handlers
    function handleClickOutside(event) {
      if (!event.target.closest('.universal-select')) {
        closeDropdown()
      }
    }

    // Watchers
    watch(() => props.modelValue, (newValue) => {
      selectedValue.value = newValue
    })

    watch(() => props.preloadData, (newData) => {
      apiOptions.value = [...newData]
    }, { immediate: true })

    watch(searchQuery, (newTerm) => {
      if (props.apiUrl && (newTerm.length >= 2 || newTerm.length === 0)) {
        handleSearch()
      }
    })

    // Lifecycle
    onMounted(() => {
      document.addEventListener('click', handleClickOutside)
      if (props.apiUrl && props.autoLoad && apiOptions.value.length === 0) {
        loadData()
      }
    })

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside)
    })
  }
}
</script>

<style scoped>
.universal-select {
  position: relative;
  width: 100%;
}

/* Simple select styles */
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
.select-outline {
  background-color: transparent;
  border: 2px solid #ced4da;
}

.select-outline:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.select-filled {
  background-color: #f8f9fa;
  border: 1px solid transparent;
}

.select-filled:focus {
  background-color: #fff;
  border-color: #86b7fe;
}

/* Advanced select styles */
.select-container {
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
  align-items: center;
}

.selected-item {
  display: inline-flex;
  align-items: center;
  padding: 2px 8px;
  background-color: #e9ecef;
  border: 1px solid #ced4da;
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
  margin-left: 4px;
}

.remove-item:hover {
  color: #dc3545;
}

.selected-single {
  color: #495057;
}

.search-input {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  min-width: 60px;
  font-size: 0.875rem;
}

.search-input::placeholder {
  color: #6c757d;
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
  z-index: 1050;
  max-height: 300px;
  overflow-y: auto;
  background: #fff;
  border: 1px solid #ced4da;
  border-top: none;
  border-radius: 0 0 0.375rem 0.375rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 12px;
  cursor: pointer;
  border-bottom: 1px solid #f8f9fa;
  transition: background-color 0.15s ease-in-out;
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
  color: #0d6efd;
}

.dropdown-item.loading,
.dropdown-item.no-results {
  cursor: default;
  color: #6c757d;
  font-style: italic;
  justify-content: center;
}

.dropdown-item.load-more {
  text-align: center;
  color: #0d6efd;
  background-color: #f8f9fa;
  font-size: 0.875rem;
  border-top: 1px solid #e9ecef;
}

.dropdown-item.load-more:hover {
  background-color: #e9ecef;
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

/* Error styles */
.invalid-feedback {
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: #dc3545;
}

.d-block {
  display: block !important;
}

/* Loading animation */
.fa-spinner {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 576px) {
  .selected-item {
    font-size: 0.75rem;
    padding: 1px 6px;
  }

  .dropdown-menu {
    max-height: 200px;
  }
}
</style>
</script>
