/**
 * Status Enum - Trạng thái chung cho toàn bộ hệ thống
 * Sử dụng cho Users, Posts, Permissions, Categories, Series, etc.
 */

export const Status = {
  INACTIVE: 'inactive',
  ACTIVE: 'active'
}

export const StatusLabels = {
  [Status.INACTIVE]: 'Không hoạt động',
  [Status.ACTIVE]: 'Hoạt động'
}

export const StatusOptions = [
  { value: Status.ACTIVE, label: StatusLabels[Status.ACTIVE] },
  { value: Status.INACTIVE, label: StatusLabels[Status.INACTIVE] }
]

export const StatusBadgeClasses = {
  [Status.INACTIVE]: 'badge bg-secondary',
  [Status.ACTIVE]: 'badge bg-success'
}

export const StatusIcons = {
  [Status.INACTIVE]: 'fas fa-times-circle',
  [Status.ACTIVE]: 'fas fa-check-circle'
}

/**
 * Helper functions
 */
export const getStatusLabel = (status) => {
  return StatusLabels[status] || 'Không xác định'
}

export const getStatusBadgeClass = (status) => {
  return StatusBadgeClasses[status] || 'badge bg-secondary'
}

export const getStatusIcon = (status) => {
  return StatusIcons[status] || 'fas fa-question-circle'
}

export const isActive = (status) => {
  return status === Status.ACTIVE || status === 'active'
}

export const isInactive = (status) => {
  return status === Status.INACTIVE || status === 'inactive'
}

/**
 * Normalize status to string
 */
export const normalizeStatus = (status) => {
  if (status === 'active' || status === 1 || status === '1') {
    return Status.ACTIVE
  }
  if (status === 'inactive' || status === 0 || status === '0') {
    return Status.INACTIVE
  }
  return status
}

/**
 * Convert to string for form inputs
 */
export const statusToString = (status) => {
  return String(status)
}

export default {
  Status,
  StatusLabels,
  StatusOptions,
  StatusBadgeClasses,
  StatusIcons,
  getStatusLabel,
  getStatusBadgeClass,
  getStatusIcon,
  isActive,
  isInactive,
  normalizeStatus,
  statusToString
}
