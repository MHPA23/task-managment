<script setup>
import { ref, onMounted, watch } from 'vue'
import { useTaskStore } from '@/stores/useTaskStore'
import axios from 'axios'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  BarElement,
  Title
} from 'chart.js'
import { Doughnut, Bar } from 'vue-chartjs'

// Register ChartJS components
ChartJS.register(
  ArcElement,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  BarElement,
  Title
)

const taskStore = useTaskStore()
const loading = ref(true)
const stats = ref({
  totalTasks: 0,
  completedTasks: 0,
  pendingTasks: 0,
  completionRate: 0,
  categoryDistribution: [],
  recentActivity: []
})

// Chart configurations
const completionData = ref({
  labels: ['Completed', 'Pending'],
  datasets: [{
    data: [],
    backgroundColor: ['#10B981', '#F59E0B']
  }]
})

const categoryData = ref({
  labels: [],
  datasets: [{
    label: 'Tasks by Category',
    data: [],
    backgroundColor: '#6366F1'
  }]
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom'
    }
  }
}

const fetchStats = async () => {
  try {
    loading.value = true
    const response = await axios.get('/api/tasks/status')
    stats.value = response.data

    // Update completion chart
    completionData.value.datasets[0].data = [
      stats.value.completedTasks,
      stats.value.pendingTasks
    ]

    // Update category chart
    categoryData.value.labels = stats.value.categoryDistribution.map(item => item.category)
    categoryData.value.datasets[0].data = stats.value.categoryDistribution.map(item => item.count)
  } catch (error) {
    console.error('Error fetching stats:', error)
  } finally {
    loading.value = false
  }
}

onMounted(fetchStats)

// Refresh stats when tasks are updated
watch(() => taskStore.tasksUpdated, fetchStats)
</script>

<template>
  <div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent"></div>
      </div>

      <div v-else>
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900">Total Tasks</h3>
            <p class="text-3xl font-bold text-blue-600">{{ stats.totalTasks }}</p>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900">Completed</h3>
            <p class="text-3xl font-bold text-green-600">{{ stats.completedTasks }}</p>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900">Pending</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ stats.pendingTasks }}</p>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900">Categories</h3>
            <p class="text-3xl font-bold text-purple-600">
              0
            </p>
          </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Completion Status</h3>
            <div class="h-64">
              <Doughnut
                :data="completionData"
                :options="chartOptions"
              />
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Category Distribution</h3>
            <div class="h-64">
              <Bar
                :data="categoryData"
                :options="chartOptions"
              />
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
          <div class="space-y-4">
            <div v-for="activity in stats.recentActivity" :key="activity.id" 
                 class="flex items-center justify-between border-b pb-4">
              <div>
                <p class="font-medium text-gray-900">{{ activity.title }}</p>
                <p class="text-sm text-gray-500">{{ activity.description }}</p>
              </div>
              <span :class="{
                'bg-green-100 text-green-800': activity.type === 'completed',
                'bg-blue-100 text-blue-800': activity.type === 'created',
                'bg-yellow-100 text-yellow-800': activity.type === 'updated'
              }" class="px-3 py-1 rounded-full text-sm font-medium">
                {{ activity.type }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>