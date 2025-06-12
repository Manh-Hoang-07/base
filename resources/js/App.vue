<template>
  <div id="app">
    <!-- Debug info -->
    <div v-if="showDebug" style="position: fixed; top: 0; right: 0; background: rgba(0,0,0,0.8); color: white; padding: 10px; z-index: 9999; font-size: 12px;">
      <div>Vue App: ✓ Loaded</div>
      <div>Route: {{ $route?.path || 'Unknown' }}</div>
      <div>Components: {{ componentCount }}</div>
      <button @click="showDebug = false" style="background: red; color: white; border: none; padding: 2px 5px; margin-top: 5px;">Hide</button>
    </div>

    <!-- Main app content -->
    <router-view v-slot="{ Component }">
      <Suspense>
        <template #default>
          <component :is="Component" />
        </template>
        <template #fallback>
          <div class="loading-fallback">
            <div class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="mt-3">Đang tải...</p>
            </div>
          </div>
        </template>
      </Suspense>
    </router-view>

    <!-- Global components -->
    <ToastContainer />
    <GlobalConfirm />

    <!-- Debug toggle -->
    <button
      @click="showDebug = !showDebug"
      style="position: fixed; bottom: 10px; right: 10px; background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 3px; z-index: 9998;"
      title="Toggle Debug Info"
    >
      Debug
    </button>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import ToastContainer from './components/ToastContainer.vue'
import GlobalConfirm from './components/GlobalConfirm.vue'

export default {
  name: 'App',
  components: {
    ToastContainer,
    GlobalConfirm
  },
  setup() {
    const showDebug = ref(false)

    const componentCount = computed(() => {
      return Object.keys(this?.$options?.components || {}).length
    })

    onMounted(() => {
      console.log('App.vue mounted successfully')

      // Remove loading screen
      const loadingElement = document.getElementById('app-loading')
      if (loadingElement) {
        loadingElement.style.display = 'none'
      }

      // Show debug info for 3 seconds
      showDebug.value = true
      setTimeout(() => {
        showDebug.value = false
      }, 3000)
    })

    return {
      showDebug,
      componentCount
    }
  }
}
</script>

<style>
.loading-fallback {
  min-height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Global styles */
#app {
  min-height: 100vh;
}

/* Debug styles */
.debug-info {
  font-family: monospace;
  font-size: 11px;
  line-height: 1.3;
}
</style>

<style>
/* Global styles */
#app {
  min-height: 100vh;
}

/* Loading animation */
.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 200px;
}

.spinner-border {
  width: 3rem;
  height: 3rem;
}

/* Fade transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Slide transition */
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from {
  transform: translateX(30px);
  opacity: 0;
}

.slide-leave-to {
  transform: translateX(-30px);
  opacity: 0;
}
</style>
