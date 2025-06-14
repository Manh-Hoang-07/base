/**
 * Status Helper Utilities
 */

export const STATUS = {
  INACTIVE: 0,
  ACTIVE: 1
}

export const STATUS_OPTIONS = [
  { value: STATUS.ACTIVE, label: 'Hoạt động' },
  { value: STATUS.INACTIVE, label: 'Không hoạt động' }
]

/**
 * Get status label
 */
export function getStatusLabel(status) {
  switch (status) {
    case STATUS.ACTIVE:
    case 'active':
    case 1:
    case '1':
      return 'Hoạt động'
    case STATUS.INACTIVE:
    case 'inactive':
    case 0:
    case '0':
      return 'Không hoạt động'
    default:
      return 'Không xác định'
  }
}

/**
 * Get status badge class
 */
export function getStatusBadgeClass(status) {
  switch (status) {
    case STATUS.ACTIVE:
    case 'active':
    case 1:
    case '1':
      return 'badge bg-success'
    case STATUS.INACTIVE:
    case 'inactive':
    case 0:
    case '0':
      return 'badge bg-danger'
    default:
      return 'badge bg-secondary'
  }
}

/**
 * Get status icon
 */
export function getStatusIcon(status) {
  switch (status) {
    case STATUS.ACTIVE:
    case 'active':
    case 1:
    case '1':
      return 'fas fa-check-circle'
    case STATUS.INACTIVE:
    case 'inactive':
    case 0:
    case '0':
      return 'fas fa-times-circle'
    default:
      return 'fas fa-question-circle'
  }
}

/**
 * Check if status is active
 */
export function isActive(status) {
  return status === STATUS.ACTIVE || status === 'active' || status === 1 || status === '1'
}

/**
 * Check if status is inactive
 */
export function isInactive(status) {
  return status === STATUS.INACTIVE || status === 'inactive' || status === 0 || status === '0'
}

/**
 * Toggle status
 */
export function toggleStatus(status) {
  return isActive(status) ? STATUS.INACTIVE : STATUS.ACTIVE
}

/**
 * Normalize status to integer
 */
export function normalizeStatus(status) {
  return isActive(status) ? STATUS.ACTIVE : STATUS.INACTIVE
}

/**
 * Convert status to boolean
 */
export function statusToBoolean(status) {
  return isActive(status)
}

/**
 * Convert boolean to status
 */
export function booleanToStatus(active) {
  return active ? STATUS.ACTIVE : STATUS.INACTIVE
}
