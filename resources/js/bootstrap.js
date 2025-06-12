import axios from 'axios';

// Configure axios
window.axios = axios;

// Set default headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';

// Set CSRF token if available
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Set base URL
window.axios.defaults.baseURL = window.Laravel?.apiUrl || '/api';

// Request interceptor
window.axios.interceptors.request.use(
    config => {
        // Add auth token if available
        const authToken = localStorage.getItem('token');
        if (authToken) {
            config.headers.Authorization = `Bearer ${authToken}`;
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

// Response interceptor
window.axios.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        // Handle 401 errors (unauthorized)
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            // Redirect to login if not already there
            if (!window.location.pathname.includes('/auth/login')) {
                window.location.href = '/auth/login';
            }
        }

        // Handle 403 errors (forbidden)
        if (error.response?.status === 403) {
            console.warn('Access forbidden:', error.response.data?.message);
        }

        return Promise.reject(error);
    }
);

// Make axios available globally for Vue components
export default axios;
