<template>
  <div id="app">


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


  </div>
</template>

<script>
import { onMounted } from 'vue'
import ToastContainer from './components/ToastContainer.vue'
import GlobalConfirm from './components/GlobalConfirm.vue'

export default {
  name: 'App',
  components: {
    ToastContainer,
    GlobalConfirm
  },
  setup() {
    onMounted(() => {
      console.log('App.vue mounted successfully')

      // Remove loading screen
      const loadingElement = document.getElementById('app-loading')
      if (loadingElement) {
        loadingElement.style.display = 'none'
      }
    })

    return {}
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
