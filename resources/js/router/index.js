import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

// Import layouts
import HomeLayout from '../layouts/HomeLayout.vue'
import AdminLayout from '../layouts/AdminLayout.vue'
import TestLayout from '../layouts/TestLayout.vue'
import AuthLayout from '../layouts/AuthLayout.vue'

// Import pages
import Home from '../pages/Home.vue'
import PostDetail from '../pages/PostDetail.vue'

// Auth pages
import Login from '../pages/auth/Login.vue'
import Register from '../pages/auth/Register.vue'

// Admin pages
import AdminDashboard from '../pages/admin/Dashboard.vue'
import AdminUsers from '../pages/admin/users/Index.vue'
import AdminUserCreate from '../pages/admin/users/Create.vue'
import AdminUserEdit from '../pages/admin/users/Edit.vue'
import AdminRoles from '../pages/admin/roles/Index.vue'
import AdminRoleCreate from '../pages/admin/roles/Create.vue'
import AdminRoleEdit from '../pages/admin/roles/Edit.vue'
import AdminPosts from '../pages/admin/posts/Index.vue'
import AdminPostCreate from '../pages/admin/posts/Create.vue'
import AdminPostEdit from '../pages/admin/posts/Edit.vue'
import TestComponent from '../pages/admin/TestComponent.vue'

const routes = [
  // Home routes
  {
    path: '/',
    component: HomeLayout,
    children: [
      {
        path: '',
        name: 'home',
        component: Home
      },
      {
        path: '/posts/:id',
        name: 'post.detail',
        component: PostDetail,
        props: true
      }
    ]
  },

  // Auth routes
  {
    path: '/auth',
    component: AuthLayout,
    children: [
      {
        path: 'login',
        name: 'login',
        component: Login,
        meta: { guest: true }
      },
      {
        path: 'register',
        name: 'register',
        component: Register,
        meta: { guest: true }
      }
    ]
  },

  // Admin routes
  {
    path: '/admin',
    component: AdminLayout, // Back to original AdminLayout
    meta: { requiresAuth: false, requiresAdmin: false }, // Keep auth disabled for now
    children: [
      {
        path: '',
        name: 'admin.dashboard',
        component: AdminDashboard
      },
      {
        path: 'users',
        name: 'admin.users',
        component: AdminUsers
      },
      {
        path: 'users/create',
        name: 'admin.users.create',
        component: AdminUserCreate
      },
      {
        path: 'users/:id/edit',
        name: 'admin.users.edit',
        component: AdminUserEdit,
        props: true
      },
      {
        path: 'roles',
        name: 'admin.roles',
        component: AdminRoles
      },
      {
        path: 'roles/create',
        name: 'admin.roles.create',
        component: AdminRoleCreate
      },
      {
        path: 'roles/:id/edit',
        name: 'admin.roles.edit',
        component: AdminRoleEdit,
        props: true
      },
      {
        path: 'posts',
        name: 'admin.posts',
        component: AdminPosts
      },
      {
        path: 'posts/create',
        name: 'admin.posts.create',
        component: AdminPostCreate
      },
      {
        path: 'posts/:id/edit',
        name: 'admin.posts.edit',
        component: AdminPostEdit,
        props: true
      },
      {
        path: 'permissions',
        name: 'admin.permissions',
        component: () => import('../pages/admin/permissions/Index.vue')
      },
      {
        path: 'categories',
        name: 'admin.categories',
        component: () => import('../pages/admin/categories/Index.vue')
      },
      {
        path: 'series',
        name: 'admin.series',
        component: () => import('../pages/admin/series/Index.vue')
      },
      {
        path: 'profile',
        name: 'admin.profile',
        component: () => import('../pages/admin/Profile.vue')
      },
      {
        path: 'settings',
        name: 'admin.settings',
        component: () => import('../pages/admin/Settings.vue')
      },
      {
        path: 'test',
        name: 'admin.test',
        component: TestComponent
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
})

// Navigation guards
router.beforeEach(async (to, from, next) => {
  try {
    const authStore = useAuthStore()

    // Skip auth checks for now - just allow all routes
    console.log('Navigating to:', to.path)
    next()

    // TODO: Re-enable auth checks later
    /*
    // Check if user is authenticated
    if (!authStore.user && authStore.token) {
      try {
        await authStore.fetchUser()
      } catch (error) {
        authStore.logout()
      }
    }

    // Handle guest routes (login, register)
    if (to.meta.guest && authStore.isAuthenticated) {
      return next({ name: 'home' })
    }

    // Handle protected routes
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
      return next({ name: 'login' })
    }

    // Handle admin routes
    if (to.meta.requiresAdmin && !authStore.isAdmin) {
      return next({ name: 'home' })
    }

    next()
    */
  } catch (error) {
    console.error('Router guard error:', error)
    next()
  }
})

export default router
