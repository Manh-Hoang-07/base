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

// Set base URL from Laravel config
if (window.Laravel && window.Laravel.apiUrl) {
    window.axios.defaults.baseURL = window.Laravel.apiUrl;
} else {
    window.axios.defaults.baseURL = 'http://web.local/api';
}

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
        }

        return Promise.reject(error);
    }
);

// Make axios available globally for Vue components
export default axios;
