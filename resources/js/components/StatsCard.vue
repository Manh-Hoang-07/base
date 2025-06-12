<template>
  <div class="card stats-card h-100" :class="borderClass">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-uppercase mb-1" :class="textClass">
            {{ title }}
          </div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">
            <span v-if="loading" class="placeholder-glow">
              <span class="placeholder col-6"></span>
            </span>
            <span v-else>{{ formattedValue }}</span>
          </div>
          <div v-if="subtitle" class="text-muted small mt-1">
            {{ subtitle }}
          </div>
        </div>
        <div class="col-auto">
          <i :class="icon" class="fa-2x text-gray-300"></i>
        </div>
      </div>
      
      <!-- Progress bar (optional) -->
      <div v-if="showProgress" class="mt-3">
        <div class="progress progress-sm">
          <div 
            class="progress-bar" 
            :class="progressClass"
            :style="{ width: progressPercent + '%' }"
          ></div>
        </div>
        <div class="d-flex justify-content-between text-xs mt-1">
          <span>{{ progressText }}</span>
          <span>{{ progressPercent }}%</span>
        </div>
      </div>

      <!-- Trend indicator (optional) -->
      <div v-if="trend" class="mt-2">
        <span class="text-xs" :class="trendClass">
          <i :class="trendIcon" class="me-1"></i>
          {{ Math.abs(trend.value) }}{{ trend.type === 'percent' ? '%' : '' }}
          {{ trend.period }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'StatsCard',
  props: {
    title: {
      type: String,
      required: true
    },
    value: {
      type: [Number, String],
      default: 0
    },
    subtitle: {
      type: String,
      default: ''
    },
    icon: {
      type: String,
      required: true
    },
    color: {
      type: String,
      default: 'primary',
      validator: (value) => ['primary', 'success', 'info', 'warning', 'danger'].includes(value)
    },
    loading: {
      type: Boolean,
      default: false
    },
    format: {
      type: String,
      default: 'number',
      validator: (value) => ['number', 'currency', 'percent'].includes(value)
    },
    showProgress: {
      type: Boolean,
      default: false
    },
    progressValue: {
      type: Number,
      default: 0
    },
    progressMax: {
      type: Number,
      default: 100
    },
    progressText: {
      type: String,
      default: ''
    },
    trend: {
      type: Object,
      default: null
      // { value: 12, type: 'percent', period: 'so với tháng trước', direction: 'up' }
    }
  },
  setup(props) {
    const borderClass = computed(() => {
      return `border-left-${props.color}`
    })

    const textClass = computed(() => {
      return `text-${props.color}`
    })

    const progressClass = computed(() => {
      return `bg-${props.color}`
    })

    const progressPercent = computed(() => {
      return Math.round((props.progressValue / props.progressMax) * 100)
    })

    const formattedValue = computed(() => {
      if (props.loading) return '...'
      
      const value = Number(props.value) || 0
      
      switch (props.format) {
        case 'currency':
          return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
          }).format(value)
        case 'percent':
          return `${value}%`
        default:
          return new Intl.NumberFormat('vi-VN').format(value)
      }
    })

    const trendClass = computed(() => {
      if (!props.trend) return ''
      
      const direction = props.trend.direction || (props.trend.value > 0 ? 'up' : 'down')
      return direction === 'up' ? 'text-success' : 'text-danger'
    })

    const trendIcon = computed(() => {
      if (!props.trend) return ''
      
      const direction = props.trend.direction || (props.trend.value > 0 ? 'up' : 'down')
      return direction === 'up' ? 'fas fa-arrow-up' : 'fas fa-arrow-down'
    })

    return {
      borderClass,
      textClass,
      progressClass,
      progressPercent,
      formattedValue,
      trendClass,
      trendIcon
    }
  }
}
</script>

<style scoped>
.stats-card {
  border: none;
  box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
  transition: all 0.3s ease;
}

.stats-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2);
}

.border-left-primary {
  border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
  border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
  border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
  border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
  border-left: 0.25rem solid #e74a3b !important;
}

.text-xs {
  font-size: 0.7rem;
}

.font-weight-bold {
  font-weight: 700;
}

.text-gray-800 {
  color: #5a5c69;
}

.text-gray-300 {
  color: #dddfeb;
}

.progress-sm {
  height: 0.5rem;
}

.placeholder {
  background-color: #e9ecef;
  border-radius: 0.25rem;
}

.placeholder-glow .placeholder {
  animation: placeholder-glow 2s ease-in-out infinite alternate;
}

@keyframes placeholder-glow {
  50% {
    opacity: 0.5;
  }
}
</style>
