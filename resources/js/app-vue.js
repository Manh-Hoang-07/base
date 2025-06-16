import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'
import { useAuthStore } from './stores/auth'

// Import axios configuration
import './bootstrap'

// Import Bootstrap JS
import 'bootstrap'

// Import SCSS (includes Bootstrap CSS and custom styles)
import '../scss/app.scss'



// Error handling
window.addEventListener('error', (e) => {
  console.error('Global error:', e.error)
})

window.addEventListener('unhandledrejection', (e) => {
  console.error('Unhandled promise rejection:', e.reason)
})

// Check if DOM is ready and initialize
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initVueApp)
} else {
  initVueApp()
}

function initVueApp() {
  try {
    // Check if app element exists
    const appElement = document.getElementById('app')
    if (!appElement) {
      throw new Error('App element #app not found')
    }

    // Create Vue app
    const app = createApp(App)

    // Use plugins
    const pinia = createPinia()
    app.use(pinia)
    app.use(router)

    // Global error handler
    app.config.errorHandler = (err, vm, info) => {
      console.error('Vue error:', err, info)
    }

    // Mount app
    app.mount('#app')

    // Initialize auth store after mount
    setTimeout(() => {
      try {
        const authStore = useAuthStore()
        authStore.initializeAuth()
      } catch (error) {
        console.error('Auth store initialization error:', error)
      }
    }, 100)

  } catch (error) {
    console.error('Failed to mount Vue app:', error)
    showError(error)
  }
}

function showError(error) {
  // Fallback: show error message
  const appElement = document.getElementById('app')
  if (appElement) {
    appElement.innerHTML = `
      <div style="padding: 20px; text-align: center; color: red;">
        <h2>Lỗi tải ứng dụng</h2>
        <p>Có lỗi xảy ra khi tải ứng dụng Vue.js</p>
        <p><strong>Lỗi:</strong> ${error.message}</p>
        <button onclick="location.reload()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
          Tải lại trang
        </button>
      </div>
    `
  }
}
