<template>
  <nav aria-label="breadcrumb" class="breadcrumb-nav">
    <ol class="breadcrumb">
      <!-- Home breadcrumb -->
      <li class="breadcrumb-item">
        <router-link to="/admin" class="breadcrumb-link">
          <i class="fas fa-home me-1"></i>
          {{ homeText }}
        </router-link>
      </li>

      <!-- Custom items -->
      <li
        v-for="(item, index) in items"
        :key="index"
        class="breadcrumb-item"
        :class="{ active: index === items.length - 1 }"
      >
        <router-link
          v-if="item.to && index !== items.length - 1"
          :to="item.to"
          class="breadcrumb-link"
        >
          <i v-if="item.icon" :class="item.icon" class="me-1"></i>
          {{ item.text }}
        </router-link>
        <span v-else class="breadcrumb-current">
          <i v-if="item.icon" :class="item.icon" class="me-1"></i>
          {{ item.text }}
        </span>
      </li>

      <!-- Auto-generated items from route -->
      <li
        v-for="(segment, index) in autoSegments"
        :key="`auto-${index}`"
        class="breadcrumb-item"
        :class="{ active: index === autoSegments.length - 1 }"
      >
        <router-link
          v-if="segment.to && index !== autoSegments.length - 1"
          :to="segment.to"
          class="breadcrumb-link"
        >
          <i v-if="segment.icon" :class="segment.icon" class="me-1"></i>
          {{ segment.text }}
        </router-link>
        <span v-else class="breadcrumb-current">
          <i v-if="segment.icon" :class="segment.icon" class="me-1"></i>
          {{ segment.text }}
        </span>
      </li>
    </ol>

    <!-- Actions (optional) -->
    <div v-if="$slots.actions" class="breadcrumb-actions">
      <slot name="actions"></slot>
    </div>
  </nav>
</template>

<script>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

export default {
  name: 'Breadcrumb',
  props: {
    items: {
      type: Array,
      default: () => []
    },
    homeText: {
      type: String,
      default: 'Trang chủ'
    },
    autoGenerate: {
      type: Boolean,
      default: true
    },
    separator: {
      type: String,
      default: '/'
    }
  },
  setup(props) {
    const route = useRoute()

    const autoSegments = computed(() => {
      if (!props.autoGenerate || props.items.length > 0) {
        return []
      }

      const segments = []
      const pathSegments = route.path.split('/').filter(segment => segment)

      // Skip if we're at home
      if (pathSegments.length === 0) {
        return []
      }

      let currentPath = ''

      for (let i = 0; i < pathSegments.length; i++) {
        const segment = pathSegments[i]
        currentPath += `/${segment}`
        const isLast = i === pathSegments.length - 1

        let text = segment
        let icon = null
        let to = isLast ? null : currentPath

        // Customize segment text and icons based on common patterns
        switch (segment) {
          case 'admin':
            text = 'Quản trị'
            icon = 'fas fa-cog'
            break
          case 'users':
            text = 'Users'
            icon = 'fas fa-users'
            break
          case 'roles':
            text = 'Roles'
            icon = 'fas fa-user-shield'
            break
          case 'posts':
            text = 'Posts'
            icon = 'fas fa-newspaper'
            break
          case 'categories':
            text = 'Danh mục'
            icon = 'fas fa-folder'
            break
          case 'series':
            text = 'Series'
            icon = 'fas fa-list'
            break
          case 'create':
            text = 'Tạo mới'
            icon = 'fas fa-plus'
            break
          case 'edit':
            text = 'Chỉnh sửa'
            icon = 'fas fa-edit'
            break
          default:
            // If it's a number, it might be an ID
            if (/^\d+$/.test(segment)) {
              text = `#${segment}`
              icon = 'fas fa-hashtag'
            }
        }

        segments.push({ text, icon, to })
      }

      return segments
    })

    return {
      autoSegments
    }
  }
}
</script>

<style scoped>
.breadcrumb-nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  padding: 0.75rem 0;
}

.breadcrumb {
  background: none;
  padding: 0;
  margin: 0;
  font-size: 0.875rem;
}

.breadcrumb-item {
  display: flex;
  align-items: center;
}

.breadcrumb-item + .breadcrumb-item::before {
  content: var(--bs-breadcrumb-divider, "/");
  float: left;
  padding-right: var(--bs-breadcrumb-item-padding-x, 0.5rem);
  padding-left: var(--bs-breadcrumb-item-padding-x, 0.5rem);
  color: var(--bs-breadcrumb-divider-color, #6c757d);
}

.breadcrumb-link {
  color: #0d6efd;
  text-decoration: none;
  transition: color 0.15s ease-in-out;
}

.breadcrumb-link:hover {
  color: #0a58ca;
  text-decoration: underline;
}

.breadcrumb-current {
  color: #6c757d;
  font-weight: 500;
}

.breadcrumb-item.active .breadcrumb-current {
  color: #495057;
}

.breadcrumb-actions {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

/* Responsive */
@media (max-width: 768px) {
  .breadcrumb-nav {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }

  .breadcrumb {
    font-size: 0.8rem;
  }

  .breadcrumb-actions {
    width: 100%;
    justify-content: flex-end;
  }
}

/* Dark theme support */
@media (prefers-color-scheme: dark) {
  .breadcrumb-link {
    color: #6ea8fe;
  }

  .breadcrumb-link:hover {
    color: #9ec5fe;
  }

  .breadcrumb-current {
    color: #adb5bd;
  }

  .breadcrumb-item.active .breadcrumb-current {
    color: #f8f9fa;
  }
}

/* Animation for dynamic breadcrumbs */
.breadcrumb-item {
  animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Icon spacing */
.breadcrumb-link i,
.breadcrumb-current i {
  font-size: 0.8em;
}

/* Truncate long text */
.breadcrumb-link,
.breadcrumb-current {
  max-width: 150px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

@media (max-width: 576px) {
  .breadcrumb-link,
  .breadcrumb-current {
    max-width: 100px;
  }
}
</style>
