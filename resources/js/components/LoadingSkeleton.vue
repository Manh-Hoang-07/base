<template>
  <div class="loading-skeleton">
    <!-- Table skeleton -->
    <div v-if="type === 'table'" class="skeleton-table">
      <div class="skeleton-row skeleton-header">
        <div v-for="i in columns" :key="i" class="skeleton-cell">
          <div class="skeleton-line"></div>
        </div>
      </div>
      <div v-for="row in rows" :key="row" class="skeleton-row">
        <div v-for="i in columns" :key="i" class="skeleton-cell">
          <div class="skeleton-line" :style="{ width: getRandomWidth() }"></div>
        </div>
      </div>
    </div>

    <!-- Card skeleton -->
    <div v-else-if="type === 'card'" class="skeleton-card">
      <div v-if="showImage" class="skeleton-image"></div>
      <div class="skeleton-content">
        <div class="skeleton-line skeleton-title"></div>
        <div v-for="i in lines" :key="i" class="skeleton-line" :style="{ width: getRandomWidth() }"></div>
      </div>
    </div>

    <!-- List skeleton -->
    <div v-else-if="type === 'list'" class="skeleton-list">
      <div v-for="item in items" :key="item" class="skeleton-list-item">
        <div v-if="showAvatar" class="skeleton-avatar"></div>
        <div class="skeleton-content">
          <div class="skeleton-line skeleton-title"></div>
          <div class="skeleton-line" :style="{ width: '70%' }"></div>
        </div>
      </div>
    </div>

    <!-- Stats skeleton -->
    <div v-else-if="type === 'stats'" class="skeleton-stats">
      <div v-for="stat in stats" :key="stat" class="skeleton-stat-card">
        <div class="skeleton-stat-content">
          <div class="skeleton-line skeleton-stat-label"></div>
          <div class="skeleton-line skeleton-stat-value"></div>
        </div>
        <div class="skeleton-stat-icon"></div>
      </div>
    </div>

    <!-- Form skeleton -->
    <div v-else-if="type === 'form'" class="skeleton-form">
      <div v-for="field in fields" :key="field" class="skeleton-form-field">
        <div class="skeleton-line skeleton-label"></div>
        <div class="skeleton-input"></div>
      </div>
    </div>

    <!-- Text skeleton -->
    <div v-else class="skeleton-text">
      <div v-for="i in lines" :key="i" class="skeleton-line" :style="{ width: getRandomWidth() }"></div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'LoadingSkeleton',
  props: {
    type: {
      type: String,
      default: 'text',
      validator: (value) => ['text', 'table', 'card', 'list', 'stats', 'form'].includes(value)
    },
    lines: {
      type: Number,
      default: 3
    },
    rows: {
      type: Number,
      default: 5
    },
    columns: {
      type: Number,
      default: 4
    },
    items: {
      type: Number,
      default: 5
    },
    stats: {
      type: Number,
      default: 4
    },
    fields: {
      type: Number,
      default: 5
    },
    showImage: {
      type: Boolean,
      default: false
    },
    showAvatar: {
      type: Boolean,
      default: false
    },
    animated: {
      type: Boolean,
      default: true
    }
  },
  setup(props) {
    const getRandomWidth = () => {
      const widths = ['60%', '70%', '80%', '90%', '100%']
      return widths[Math.floor(Math.random() * widths.length)]
    }

    return {
      getRandomWidth
    }
  }
}
</script>

<style scoped>
.loading-skeleton {
  width: 100%;
}

.skeleton-line {
  height: 16px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  border-radius: 4px;
  margin-bottom: 8px;
}

.skeleton-line:last-child {
  margin-bottom: 0;
}

.loading-skeleton .skeleton-line {
  animation: skeleton-loading 1.5s infinite ease-in-out;
}

@keyframes skeleton-loading {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

/* Table skeleton */
.skeleton-table {
  width: 100%;
  border-collapse: collapse;
}

.skeleton-row {
  display: flex;
  padding: 12px 0;
  border-bottom: 1px solid #f0f0f0;
}

.skeleton-header {
  background-color: #f8f9fa;
  padding: 16px 0;
}

.skeleton-cell {
  flex: 1;
  padding: 0 8px;
}

.skeleton-cell:first-child {
  padding-left: 0;
}

.skeleton-cell:last-child {
  padding-right: 0;
}

/* Card skeleton */
.skeleton-card {
  border: 1px solid #e9ecef;
  border-radius: 8px;
  overflow: hidden;
  background: white;
}

.skeleton-image {
  height: 200px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite ease-in-out;
}

.skeleton-content {
  padding: 16px;
}

.skeleton-title {
  height: 20px;
  width: 80%;
  margin-bottom: 12px;
}

/* List skeleton */
.skeleton-list {
  width: 100%;
}

.skeleton-list-item {
  display: flex;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #f0f0f0;
}

.skeleton-list-item:last-child {
  border-bottom: none;
}

.skeleton-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite ease-in-out;
  margin-right: 12px;
  flex-shrink: 0;
}

.skeleton-list-item .skeleton-content {
  flex: 1;
  padding: 0;
}

/* Stats skeleton */
.skeleton-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 16px;
}

.skeleton-stat-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  background: white;
}

.skeleton-stat-content {
  flex: 1;
}

.skeleton-stat-label {
  height: 12px;
  width: 60%;
  margin-bottom: 8px;
}

.skeleton-stat-value {
  height: 24px;
  width: 40%;
}

.skeleton-stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 8px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite ease-in-out;
}

/* Form skeleton */
.skeleton-form {
  width: 100%;
}

.skeleton-form-field {
  margin-bottom: 20px;
}

.skeleton-label {
  height: 14px;
  width: 30%;
  margin-bottom: 8px;
}

.skeleton-input {
  height: 38px;
  width: 100%;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite ease-in-out;
  border-radius: 4px;
}

/* Responsive */
@media (max-width: 768px) {
  .skeleton-stats {
    grid-template-columns: 1fr;
  }
  
  .skeleton-row {
    flex-direction: column;
  }
  
  .skeleton-cell {
    padding: 4px 0;
  }
}

/* Disable animation if user prefers reduced motion */
@media (prefers-reduced-motion: reduce) {
  .skeleton-line,
  .skeleton-image,
  .skeleton-avatar,
  .skeleton-stat-icon,
  .skeleton-input {
    animation: none;
  }
}
</style>
