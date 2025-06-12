import { reactive } from 'vue'

// Global confirm state
const confirmConfig = reactive({
  show: false,
  type: 'warning',
  title: 'Xác nhận',
  message: '',
  description: '',
  confirmText: 'Xác nhận',
  cancelText: 'Hủy',
  loadingText: 'Đang xử lý...'
})

let confirmResolve = null

export function useConfirm() {
  const showConfirm = (options = {}) => {
    return new Promise((resolve) => {
      Object.assign(confirmConfig, {
        show: true,
        type: options.type || 'warning',
        title: options.title || 'Xác nhận',
        message: options.message || 'Bạn có chắc chắn muốn thực hiện hành động này?',
        description: options.description || '',
        confirmText: options.confirmText || 'Xác nhận',
        cancelText: options.cancelText || 'Hủy',
        loadingText: options.loadingText || 'Đang xử lý...'
      })

      confirmResolve = resolve
    })
  }

  const hideConfirm = () => {
    confirmConfig.show = false
    if (confirmResolve) {
      confirmResolve(false)
      confirmResolve = null
    }
  }

  const handleConfirm = () => {
    if (confirmResolve) {
      confirmResolve(true)
      confirmResolve = null
    }
    confirmConfig.show = false
  }

  const handleCancel = () => {
    if (confirmResolve) {
      confirmResolve(false)
      confirmResolve = null
    }
    confirmConfig.show = false
  }

  // Predefined confirm types
  const confirmDelete = (itemName = 'mục này') => {
    return showConfirm({
      type: 'danger',
      title: 'Xác nhận xóa',
      message: `Bạn có chắc chắn muốn xóa ${itemName}?`,
      description: 'Hành động này không thể hoàn tác.',
      confirmText: 'Xóa',
      cancelText: 'Hủy'
    })
  }

  const confirmBulkDelete = (count = 0) => {
    return showConfirm({
      type: 'danger',
      title: 'Xác nhận xóa hàng loạt',
      message: `Bạn có chắc chắn muốn xóa ${count} mục đã chọn?`,
      description: 'Hành động này không thể hoàn tác.',
      confirmText: 'Xóa tất cả',
      cancelText: 'Hủy'
    })
  }

  const confirmSave = (message = 'Bạn có muốn lưu thay đổi?') => {
    return showConfirm({
      type: 'info',
      title: 'Xác nhận lưu',
      message,
      confirmText: 'Lưu',
      cancelText: 'Hủy'
    })
  }

  const confirmPublish = (itemName = 'bài viết này') => {
    return showConfirm({
      type: 'success',
      title: 'Xác nhận xuất bản',
      message: `Bạn có muốn xuất bản ${itemName}?`,
      description: 'Nội dung sẽ hiển thị công khai sau khi xuất bản.',
      confirmText: 'Xuất bản',
      cancelText: 'Hủy'
    })
  }

  const confirmUnpublish = (itemName = 'bài viết này') => {
    return showConfirm({
      type: 'warning',
      title: 'Xác nhận ẩn',
      message: `Bạn có muốn ẩn ${itemName}?`,
      description: 'Nội dung sẽ không hiển thị công khai sau khi ẩn.',
      confirmText: 'Ẩn',
      cancelText: 'Hủy'
    })
  }

  const confirmStatusChange = (currentStatus, itemName = 'mục này') => {
    const newStatus = currentStatus ? 'vô hiệu hóa' : 'kích hoạt'
    const type = currentStatus ? 'warning' : 'success'

    return showConfirm({
      type,
      title: `Xác nhận ${newStatus}`,
      message: `Bạn có muốn ${newStatus} ${itemName}?`,
      confirmText: newStatus === 'kích hoạt' ? 'Kích hoạt' : 'Vô hiệu hóa',
      cancelText: 'Hủy'
    })
  }

  const confirmLogout = () => {
    return showConfirm({
      type: 'warning',
      title: 'Xác nhận đăng xuất',
      message: 'Bạn có chắc chắn muốn đăng xuất?',
      description: 'Bạn sẽ cần đăng nhập lại để tiếp tục sử dụng.',
      confirmText: 'Đăng xuất',
      cancelText: 'Hủy'
    })
  }

  const confirmLeave = () => {
    return showConfirm({
      type: 'warning',
      title: 'Xác nhận rời khỏi trang',
      message: 'Bạn có thay đổi chưa được lưu.',
      description: 'Nếu rời khỏi trang, các thay đổi sẽ bị mất.',
      confirmText: 'Rời khỏi',
      cancelText: 'Ở lại'
    })
  }

  const confirmReset = () => {
    return showConfirm({
      type: 'warning',
      title: 'Xác nhận đặt lại',
      message: 'Bạn có muốn đặt lại tất cả thay đổi?',
      description: 'Tất cả dữ liệu đã nhập sẽ bị xóa.',
      confirmText: 'Đặt lại',
      cancelText: 'Hủy'
    })
  }

  return {
    confirmConfig,
    showConfirm,
    hideConfirm,
    handleConfirm,
    handleCancel,
    // Predefined confirms
    confirmDelete,
    confirmBulkDelete,
    confirmSave,
    confirmPublish,
    confirmUnpublish,
    confirmStatusChange,
    confirmLogout,
    confirmLeave,
    confirmReset
  }
}
