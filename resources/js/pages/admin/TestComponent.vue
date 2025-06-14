<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Test Component</h5>
          </div>
          <div class="card-body">
            <p>Đây là test component để kiểm tra Vue.js hoạt động</p>
            <p>Counter: {{ counter }}</p>
            <button @click="increment" class="btn btn-primary">Tăng</button>
            <button @click="decrement" class="btn btn-secondary ms-2">Giảm</button>
            
            <hr>
            
            <h6>Test Data:</h6>
            <ul>
              <li v-for="item in testData" :key="item.id">
                {{ item.name }} - {{ item.email }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
  name: 'TestComponent',
  setup() {
    const counter = ref(0)
    const testData = ref([
      { id: 1, name: 'User 1', email: 'user1@example.com' },
      { id: 2, name: 'User 2', email: 'user2@example.com' },
      { id: 3, name: 'User 3', email: 'user3@example.com' }
    ])

    const increment = () => {
      counter.value++
      console.log('Counter incremented:', counter.value)
    }

    const decrement = () => {
      counter.value--
      console.log('Counter decremented:', counter.value)
    }

    onMounted(() => {
      console.log('Test component mounted successfully!')
    })

    return {
      counter,
      testData,
      increment,
      decrement
    }
  }
}
</script>
