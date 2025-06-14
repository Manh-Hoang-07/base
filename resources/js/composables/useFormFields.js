/**
 * Composable for common form fields
 */
import { STATUS_OPTIONS } from '../utils/statusHelper'

/**
 * Get status field configuration
 */
export function getStatusField(options = {}) {
  return {
    name: 'status',
    label: 'Trạng thái',
    type: 'status',
    required: options.required !== false,
    help: options.help || 'Chọn trạng thái hoạt động',
    ...options
  }
}

/**
 * Get name field configuration
 */
export function getNameField(options = {}) {
  return {
    name: 'name',
    label: 'Tên',
    type: 'text',
    required: true,
    placeholder: 'Nhập tên...',
    ...options
  }
}

/**
 * Get title field configuration
 */
export function getTitleField(options = {}) {
  return {
    name: 'title',
    label: 'Tiêu đề',
    type: 'text',
    required: true,
    placeholder: 'Nhập tiêu đề...',
    ...options
  }
}

/**
 * Get description field configuration
 */
export function getDescriptionField(options = {}) {
  return {
    name: 'description',
    label: 'Mô tả',
    type: 'textarea',
    required: false,
    placeholder: 'Nhập mô tả...',
    rows: 3,
    ...options
  }
}

/**
 * Get email field configuration
 */
export function getEmailField(options = {}) {
  return {
    name: 'email',
    label: 'Email',
    type: 'email',
    required: true,
    placeholder: 'Nhập email...',
    ...options
  }
}

/**
 * Get password field configuration
 */
export function getPasswordField(options = {}) {
  return {
    name: 'password',
    label: 'Mật khẩu',
    type: 'password',
    required: true,
    placeholder: 'Nhập mật khẩu...',
    ...options
  }
}

/**
 * Get password confirmation field configuration
 */
export function getPasswordConfirmationField(options = {}) {
  return {
    name: 'password_confirmation',
    label: 'Xác nhận mật khẩu',
    type: 'password',
    required: true,
    placeholder: 'Nhập lại mật khẩu...',
    ...options
  }
}

/**
 * Get image field configuration
 */
export function getImageField(options = {}) {
  return {
    name: 'image',
    label: 'Hình ảnh',
    type: 'file',
    required: false,
    accept: 'image/*',
    help: 'Chọn file hình ảnh (JPG, PNG, GIF)',
    ...options
  }
}

/**
 * Get category select field configuration
 */
export function getCategoryField(options = {}) {
  return {
    name: 'category_id',
    label: 'Danh mục',
    type: 'base-select',
    required: true,
    placeholder: 'Chọn danh mục...',
    options: [], // Will be populated from API
    ...options
  }
}

/**
 * Get role select field configuration
 */
export function getRoleField(options = {}) {
  return {
    name: 'role_id',
    label: 'Vai trò',
    type: 'base-select',
    required: false,
    placeholder: 'Chọn vai trò...',
    options: [], // Will be populated from API
    ...options
  }
}

/**
 * Get user select field configuration
 */
export function getUserField(options = {}) {
  return {
    name: 'user_id',
    label: 'Người dùng',
    type: 'base-select',
    required: false,
    placeholder: 'Chọn người dùng...',
    options: [], // Will be populated from API
    ...options
  }
}

/**
 * Get user fields for create form
 */
export function getUserCreateFields() {
  return [
    getNameField({ label: 'Họ và tên' }),
    getEmailField(),
    getPasswordField(),
    getPasswordConfirmationField(),
    getStatusField()
  ]
}

/**
 * Get user fields for edit form
 */
export function getUserEditFields() {
  return [
    getNameField({ label: 'Họ và tên' }),
    getEmailField(),
    getStatusField()
  ]
}

/**
 * Get category fields
 */
export function getCategoryFields() {
  return [
    getNameField({ label: 'Tên danh mục' }),
    getDescriptionField(),
    getImageField(),
    getStatusField()
  ]
}

/**
 * Get role fields
 */
export function getRoleFields() {
  return [
    getNameField({ label: 'Tên vai trò' }),
    getTitleField({ label: 'Tiêu đề hiển thị' }),
    getDescriptionField(),
    getStatusField()
  ]
}

/**
 * Get post fields
 */
export function getPostFields() {
  return [
    getTitleField({ name: 'name', label: 'Tiêu đề bài viết' }),
    getDescriptionField(),
    getImageField(),
    getCategoryField(),
    getStatusField()
  ]
}

/**
 * Get permission fields
 */
export function getPermissionFields() {
  return [
    getNameField({ label: 'Tên quyền' }),
    getTitleField({ label: 'Tiêu đề hiển thị' }),
    getDescriptionField(),
    getStatusField()
  ]
}

/**
 * Common field validation rules
 */
export const VALIDATION_RULES = {
  required: (value) => !!value || 'Trường này là bắt buộc',
  email: (value) => {
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return pattern.test(value) || 'Email không hợp lệ'
  },
  minLength: (min) => (value) => 
    (value && value.length >= min) || `Tối thiểu ${min} ký tự`,
  maxLength: (max) => (value) => 
    (value && value.length <= max) || `Tối đa ${max} ký tự`,
  password: (value) => {
    if (!value) return 'Mật khẩu là bắt buộc'
    if (value.length < 8) return 'Mật khẩu tối thiểu 8 ký tự'
    return true
  },
  passwordConfirmation: (password) => (value) => 
    value === password || 'Mật khẩu xác nhận không khớp'
}

/**
 * Format form data before submit
 */
export function formatFormData(data, fields) {
  const formatted = { ...data }
  
  fields.forEach(field => {
    if (field.type === 'status' && formatted[field.name] !== undefined) {
      // Ensure status is integer
      formatted[field.name] = parseInt(formatted[field.name])
    }
    
    if (field.type === 'file' && !formatted[field.name]) {
      // Remove empty file fields
      delete formatted[field.name]
    }
  })
  
  return formatted
}
