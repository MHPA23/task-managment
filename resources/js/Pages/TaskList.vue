<template>
  <div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-gray-800">Task List</h1>
          <button 
            @click="showCreateModal = true"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            New Task
          </button>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mb-6">
          <select v-model="taskStore.sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="created_at">Sort by Date</option>
            <option value="title">Sort by Title</option>
          </select>

          <select v-model="taskStore.sortDir" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
          </select>

          <select v-model="taskStore.completed" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option :value="null">All Tasks</option>
            <option :value="true">Completed Tasks</option>
            <option :value="false">Pending Tasks</option>
          </select>

          <input
            v-model="taskStore.title"
            type="text"
            placeholder="Search by title..."
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
        </div>

        <!-- Task List -->
        <ul class="space-y-4">
          <li
            v-for="task in tasks.data"
            :key="task.id"
            class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-blue-500 transition-colors duration-200"
          >
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800">{{ task.title }}</h3>
                <p class="text-gray-600 mt-1">{{ task.description }}</p>
              </div>
              <div class="flex items-center space-x-4">
                <button 
                  @click="editTask(task)"
                  class="text-blue-500 hover:text-blue-600"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
                </button>
                <button 
                  @click="deleteTask(task)"
                  class="text-red-500 hover:text-red-600"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </button>
                <span 
                  :class="[
                    'px-3 py-1 rounded-full text-sm font-medium',
                    task.completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                  ]"
                >
                  {{ task.completed ? 'Completed' : 'Pending' }}
                </span>
              </div>
            </div>
          </li>
        </ul>

        <!-- Pagination -->
        <div v-if="tasks.meta" class="mt-6 flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Showing {{ tasks.meta.from }} to {{ tasks.meta.to }} of {{ tasks.meta.total }} tasks
          </div>
          <div class="flex space-x-2">
            <button
              @click="changePage(tasks.meta.current_page - 1)"
              :disabled="tasks.meta.current_page === 1"
              class="px-3 py-1 border rounded-md"
              :class="tasks.meta.current_page === 1 ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-50'"
            >
              Previous
            </button>
            <button
              @click="changePage(tasks.meta.current_page + 1)"
              :disabled="tasks.meta.current_page === tasks.meta.last_page"
              class="px-3 py-1 border rounded-md"
              :class="tasks.meta.current_page === tasks.meta.last_page ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-50'"
            >
              Next
            </button>
          </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="mt-6 text-center">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
          <p class="mt-2 text-gray-600">Loading tasks...</p>
        </div>

        <!-- Empty State -->
        <div v-if="!loading && (!tasks.data || tasks.data.length === 0)" class="mt-6 text-center">
          <p class="text-gray-500">No tasks found.</p>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">{{ editingTask ? 'Edit Task' : 'New Task' }}</h2>
        
        <form @submit.prevent="saveTask" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input 
              v-model="form.title"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea 
              v-model="form.description"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              rows="3"
            ></textarea>
          </div>

          <div class="flex items-center">
            <input 
              v-model="form.completed"
              type="checkbox"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            >
            <label class="ml-2 block text-sm text-gray-900">Task Completed</label>
          </div>

          <div class="flex justify-end space-x-3">
            <button 
              type="button"
              @click="showCreateModal = false"
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
            >
              {{ editingTask ? 'Save' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watchEffect } from 'vue'
import { useTaskStore } from '@/stores/useTaskStore'
import { useToast } from "vue-toastification"
import axios from 'axios'

const toast = useToast()
const taskStore = useTaskStore()
const tasks = ref({ data: [], meta: null })
const loading = ref(false)
const showCreateModal = ref(false)
const editingTask = ref(null)

const form = reactive({
  title: '',
  description: '',
  completed: false
})

const resetForm = () => {
  form.title = ''
  form.description = ''
  form.completed = false
  editingTask.value = null
}

const editTask = (task) => {
  editingTask.value = task
  form.title = task.title
  form.description = task.description
  form.completed = task.completed
  showCreateModal.value = true
}

const deleteTask = async (task) => {
  if (!confirm('Are you sure you want to delete this task?')) return
  
  try {
    await axios.delete(`/api/tasks/${task.id}`)
    await fetchTasks()
    toast.success("Task deleted successfully!")
  } catch (error) {
    console.error('Error deleting task:', error)
    toast.error("An error occurred while deleting the task")
  }
}

const saveTask = async () => {
  try {
    if (editingTask.value) {
      await axios.put(`/api/tasks/${editingTask.value.id}`, form)
      toast.success("Task updated successfully!")
    } else {
      await axios.post('/api/tasks', form)
      toast.success("Task created successfully!")
    }
    showCreateModal.value = false
    resetForm()
    await fetchTasks()
  } catch (error) {
    toast.error(error.response?.data?.error || "An error occurred while saving the task")
  }
}

const changePage = async (page) => {
  taskStore.currentPage = page
  await fetchTasks()
}

const fetchTasks = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/tasks', {
      params: {
        ...taskStore.queryParams,
        page: taskStore.currentPage,
        per_page: 10
      }
    })
    tasks.value = response.data
  } catch (error) {
    console.error('Error fetching tasks:', error)
  } finally {
    loading.value = false
  }
}

watchEffect(fetchTasks)
</script>
  