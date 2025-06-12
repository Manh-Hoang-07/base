import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'

// Import bootstrap and axios configuration
import './bootstrap'

// Import CSS
import '../css/app.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import '@fortawesome/fontawesome-free/css/all.min.css'

// Import Bootstrap JS
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Error handling
window.addEventListener('error', (e) => {
  console.error('Global error:', e.error)
})

window.addEventListener('unhandledrejection', (e) => {
  console.error('Unhandled promise rejection:', e.reason)
})

try {
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
      import('./stores/auth').then(({ useAuthStore }) => {
        const authStore = useAuthStore()
        authStore.initializeAuth()
      })
    } catch (error) {
      console.error('Auth store initialization error:', error)
    }
  }, 100)

  console.log('Vue app mounted successfully')

} catch (error) {
  console.error('Failed to mount Vue app:', error)

  // Fallback: show error message
  const appElement = document.getElementById('app')
  if (appElement) {
    appElement.innerHTML = `
      <div style="padding: 20px; text-align: center; color: red;">
        <h2>Lỗi tải ứng dụng</h2>
        <p>Có lỗi xảy ra khi tải ứng dụng Vue.js</p>
        <p>Vui lòng kiểm tra console để xem chi tiết lỗi</p>
        <button onclick="location.reload()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
          Tải lại trang
        </button>
      </div>
    `
  }
}
