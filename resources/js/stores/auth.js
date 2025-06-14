import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token'),
    loading: false
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
    isAdmin: (state) => {
      if (!state.user) return false
      // Check if user has admin role or specific permissions
      return state.user.roles?.some(role => role.name === 'admin') || false
    }
  },

  actions: {
    async login(credentials) {
      this.loading = true
      try {
        console.log('Login attempt with:', credentials.email);
        const response = await axios.post('/v1/auth/login', credentials)
        
        if (response.data.success) {
          this.token = response.data.data.token
          this.user = response.data.data.user
          
          localStorage.setItem('token', this.token)
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
          console.log('Login successful:', this.user);
          
          return { success: true, message: response.data.message }
        }
        
        return { success: false, message: response.data.message }
      } catch (error) {
        console.error('Login error:', error);
        const message = error.response?.data?.message || 'Đăng nhập thất bại'
        return { success: false, message }
      } finally {
        this.loading = false
      }
    },

    async register(userData) {
      this.loading = true
      try {
        console.log('Register attempt with:', userData.email);
        const response = await axios.post('/v1/auth/register', userData)
        
        if (response.data.success) {
          return { success: true, message: response.data.message }
        }
        
        return { success: false, message: response.data.message }
      } catch (error) {
        console.error('Register error:', error);
        const message = error.response?.data?.message || 'Đăng ký thất bại'
        return { success: false, message }
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        if (this.token) {
          await axios.post('/v1/auth/logout')
        }
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.user = null
        this.token = null
        localStorage.removeItem('token')
        delete axios.defaults.headers.common['Authorization']
      }
    },

    async fetchUser() {
      if (!this.token) return
      
      try {
        console.log('Fetching user data...');
        const response = await axios.get('/v1/auth/user')
        if (response.data.success) {
          this.user = response.data.data
          console.log('User data fetched:', this.user);
        }
      } catch (error) {
        console.error('Fetch user error:', error)
        this.logout()
      }
    },

    initializeAuth() {
      if (this.token) {
        console.log('Initializing auth with token');
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        this.fetchUser()
      }
    }
  }
})
