<template>
  <div class="select2-wrapper">
    <label v-if="label" :for="id" class="form-label">{{ label }}</label>
    <div class="select2-container" :class="{ 'is-invalid': hasError }">
      <!-- Selected items display -->
      <div class="select2-selection" @click="toggleDropdown" ref="selectionRef">
        <div class="selected-items">
          <!-- Multiple selection -->
          <template v-if="multiple">
            <span 
              v-for="item in selectedItems" 
              :key="item.id" 
              class="selected-item"
            >
              {{ item.name }}
              <button 
                type="button" 
                class="remove-item" 
                @click.stop="removeItem(item)"
              >
                <i class="fas fa-times"></i>
              </button>
            </span>
            <input
              v-if="isOpen || selectedItems.length === 0"
              ref="searchInput"
              v-model="searchTerm"
              :placeholder="placeholder"
              class="search-input"
              @input="handleSearch"
              @keydown="handleKeydown"
            />
          </template>
          
          <!-- Single selection -->
          <template v-else>
            <span v-if="selectedItems.length > 0" class="selected-single">
              {{ selectedItems[0].name }}
            </span>
            <input
              v-if="isOpen || selectedItems.length === 0"
              ref="searchInput"
              v-model="searchTerm"
              :placeholder="placeholder"
              class="search-input"
              @input="handleSearch"
              @keydown="handleKeydown"
            />
          </template>
        </div>
        
        <div class="select2-arrow">
          <i :class="isOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
        </div>
      </div>

      <!-- Dropdown -->
      <div v-if="isOpen" class="select2-dropdown" ref="dropdownRef">
        <div v-if="loading" class="select2-loading">
          <i class="fas fa-spinner fa-spin"></i> Đang tải...
        </div>
        
        <div v-else-if="filteredOptions.length === 0" class="select2-no-results">
          Không tìm thấy kết quả
        </div>
        
        <div v-else class="select2-results">
          <div
            v-for="(option, index) in filteredOptions"
            :key="option.id"
            class="select2-option"
            :class="{ 
              'selected': isSelected(option),
              'highlighted': index === highlightedIndex
            }"
            @click="selectOption(option)"
            @mouseenter="highlightedIndex = index"
          >
            <i v-if="isSelected(option)" class="fas fa-check me-2"></i>
            {{ option.name }}
          </div>
          
          <!-- Load more button -->
          <div 
            v-if="hasMore && !loading" 
            class="select2-load-more"
            @click="loadMore"
          >
            <i class="fas fa-plus"></i> Tải thêm
          </div>
        </div>
      </div>
    </div>
    
    <!-- Error message -->
    <div v-if="hasError" class="invalid-feedback d-block">
      {{ errorMessage }}
    </div>
    
    <!-- Help text -->
    <div v-if="help" class="form-text">{{ help }}</div>
  </div>
</template>

<script>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue'
import { useApi } from '../composables/useApi'

export default {
  name: 'Select2',
  props: {
    id: {
      type: String,
      default: () => `select2-${Math.random().toString(36).substr(2, 9)}`
    },
    label: String,
    placeholder: {
      type: String,
      default: 'Tìm kiếm...'
    },
    multiple: {
      type: Boolean,
      default: false
    },
    modelValue: {
      type: [Array, String, Number],
      default: () => []
    },
    apiUrl: {
      type: String,
      required: true
    },
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
    help: String,
    errorMessage: String,
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue', 'change'],
  setup(props, { emit }) {
    const { fetchList } = useApi()
    
    // Reactive data
    const isOpen = ref(false)
    const loading = ref(false)
    const searchTerm = ref('')
    const options = ref([...props.preloadData])
    const currentPage = ref(1)
    const hasMore = ref(true)
    const highlightedIndex = ref(-1)
    
    // Refs
    const selectionRef = ref(null)
    const dropdownRef = ref(null)
    const searchInput = ref(null)
    
    // Computed
    const hasError = computed(() => !!props.errorMessage)
    
    const selectedItems = computed(() => {
      if (!props.modelValue) return []
      
      const values = Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue]
      return options.value.filter(option => values.includes(option.id))
    })
    
    const filteredOptions = computed(() => {
      if (!searchTerm.value) return options.value
      
      return options.value.filter(option =>
        option.name.toLowerCase().includes(searchTerm.value.toLowerCase())
      )
    })
    
    // Methods
    const loadData = async (search = '', page = 1, append = false) => {
      if (loading.value) return
      
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
          options.value = [...options.value, ...newOptions]
        } else {
          options.value = newOptions
        }
        
        hasMore.value = newOptions.length === props.limit
        currentPage.value = page
      } catch (error) {
        console.error('Error loading select2 data:', error)
      } finally {
        loading.value = false
      }
    }
    
    const handleSearch = async () => {
      currentPage.value = 1
      hasMore.value = true
      await loadData(searchTerm.value, 1, false)
      highlightedIndex.value = -1
    }
    
    const loadMore = async () => {
      if (hasMore.value && !loading.value) {
        await loadData(searchTerm.value, currentPage.value + 1, true)
      }
    }
    
    const toggleDropdown = () => {
      if (props.disabled) return
      
      isOpen.value = !isOpen.value
      if (isOpen.value) {
        nextTick(() => {
          searchInput.value?.focus()
        })
      }
    }
    
    const selectOption = (option) => {
      if (props.multiple) {
        const currentValues = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        const index = currentValues.indexOf(option.id)
        
        if (index > -1) {
          currentValues.splice(index, 1)
        } else {
          currentValues.push(option.id)
        }
        
        emit('update:modelValue', currentValues)
        emit('change', currentValues)
      } else {
        emit('update:modelValue', option.id)
        emit('change', option.id)
        isOpen.value = false
        searchTerm.value = ''
      }
    }
    
    const removeItem = (item) => {
      if (props.multiple) {
        const currentValues = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        const index = currentValues.indexOf(item.id)
        if (index > -1) {
          currentValues.splice(index, 1)
          emit('update:modelValue', currentValues)
          emit('change', currentValues)
        }
      }
    }
    
    const isSelected = (option) => {
      if (props.multiple) {
        return Array.isArray(props.modelValue) && props.modelValue.includes(option.id)
      }
      return props.modelValue === option.id
    }
    
    const handleKeydown = (event) => {
      switch (event.key) {
        case 'ArrowDown':
          event.preventDefault()
          if (highlightedIndex.value < filteredOptions.value.length - 1) {
            highlightedIndex.value++
          }
          break
        case 'ArrowUp':
          event.preventDefault()
          if (highlightedIndex.value > 0) {
            highlightedIndex.value--
          }
          break
        case 'Enter':
          event.preventDefault()
          if (highlightedIndex.value >= 0 && filteredOptions.value[highlightedIndex.value]) {
            selectOption(filteredOptions.value[highlightedIndex.value])
          }
          break
        case 'Escape':
          isOpen.value = false
          break
      }
    }
    
    const handleClickOutside = (event) => {
      if (selectionRef.value && !selectionRef.value.contains(event.target) &&
          dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isOpen.value = false
      }
    }
    
    // Watchers
    watch(() => props.preloadData, (newData) => {
      options.value = [...newData]
    }, { immediate: true })
    
    watch(searchTerm, (newTerm) => {
      if (newTerm.length >= 2 || newTerm.length === 0) {
        handleSearch()
      }
    })
    
    // Lifecycle
    onMounted(() => {
      document.addEventListener('click', handleClickOutside)
      if (options.value.length === 0) {
        loadData()
      }
    })
    
    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside)
    })
    
    return {
      isOpen,
      loading,
      searchTerm,
      options,
      hasMore,
      highlightedIndex,
      selectionRef,
      dropdownRef,
      searchInput,
      hasError,
      selectedItems,
      filteredOptions,
      toggleDropdown,
      selectOption,
      removeItem,
      isSelected,
      handleKeydown,
      handleSearch,
      loadMore
    }
  }
}
</script>

<style scoped>
.select2-wrapper {
  position: relative;
}

.select2-container {
  position: relative;
  width: 100%;
}

.select2-selection {
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

.select2-selection:hover {
  border-color: #86b7fe;
}

.select2-selection:focus-within {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.select2-container.is-invalid .select2-selection {
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
}

.remove-item:hover {
  color: #dc3545;
}

.selected-single {
  color: #495057;
}

.search-input {
  border: none;
  outline: none;
  background: transparent;
  flex: 1;
  min-width: 100px;
  font-size: 0.875rem;
}

.search-input::placeholder {
  color: #6c757d;
}

.select2-arrow {
  color: #6c757d;
  margin-left: 8px;
}

.select2-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 1050;
  background: #fff;
  border: 1px solid #ced4da;
  border-top: none;
  border-radius: 0 0 0.375rem 0.375rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  max-height: 300px;
  overflow-y: auto;
}

.select2-loading,
.select2-no-results {
  padding: 12px;
  text-align: center;
  color: #6c757d;
  font-style: italic;
}

.select2-results {
  max-height: 280px;
  overflow-y: auto;
}

.select2-option {
  padding: 8px 12px;
  cursor: pointer;
  border-bottom: 1px solid #f8f9fa;
  transition: background-color 0.15s ease-in-out;
}

.select2-option:hover,
.select2-option.highlighted {
  background-color: #f8f9fa;
}

.select2-option.selected {
  background-color: #e7f3ff;
  color: #0d6efd;
}

.select2-option:last-child {
  border-bottom: none;
}

.select2-load-more {
  padding: 8px 12px;
  text-align: center;
  color: #0d6efd;
  cursor: pointer;
  border-top: 1px solid #e9ecef;
  background-color: #f8f9fa;
  font-size: 0.875rem;
}

.select2-load-more:hover {
  background-color: #e9ecef;
}

/* Responsive */
@media (max-width: 576px) {
  .select2-dropdown {
    max-height: 200px;
  }

  .selected-item {
    font-size: 0.75rem;
    padding: 1px 6px;
  }
}
</style>
