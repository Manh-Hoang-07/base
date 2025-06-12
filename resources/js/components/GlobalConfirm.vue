<template>
  <ConfirmDialog
    v-if="confirmConfig.show"
    modal-id="globalConfirmModal"
    :type="confirmConfig.type"
    :title="confirmConfig.title"
    :message="confirmConfig.message"
    :description="confirmConfig.description"
    :confirm-text="confirmConfig.confirmText"
    :cancel-text="confirmConfig.cancelText"
    :loading-text="confirmConfig.loadingText"
    @confirm="handleConfirm"
    @cancel="handleCancel"
  />
</template>

<script>
import { watch, onUnmounted } from 'vue'
import ConfirmDialog from './ConfirmDialog.vue'
import { useConfirm } from '../composables/useConfirm'

export default {
  name: 'GlobalConfirm',
  components: {
    ConfirmDialog
  },
  setup() {
    const { confirmConfig, handleConfirm, handleCancel } = useConfirm()

    // Watch for show changes and auto-show modal
    const stopWatcher = watch(
      () => confirmConfig.show,
      async (newValue) => {
        if (newValue) {
          // Wait for DOM update
          await new Promise(resolve => setTimeout(resolve, 100))

          const { Modal } = await import('bootstrap')
          const modalElement = document.getElementById('globalConfirmModal')
          if (modalElement) {
            const modal = new Modal(modalElement)
            modal.show()
          }
        }
      }
    )

    onUnmounted(() => {
      stopWatcher()
    })

    return {
      confirmConfig,
      handleConfirm,
      handleCancel
    }
  }
}
</script>
