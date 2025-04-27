<template>
  <Head title="Tasks" />
  <AuthenticatedLayout>
    <br>
    <div class="max-w-4xl mx-auto px-4">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-gray-800">Task List</h1>
          <button 
            @click="openCreateModal"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            New Task
          </button>
        </div>

        <!-- Filters -->
        <TaskFilters/>

        <!-- Task List -->
        <ul class="space-y-4">
          <li
            v-for="task in tasks.data"
            :key="task.id"
            class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-blue-500 transition-colors duration-200"
          >
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800">{{ task.title }}</h3> <small v-if="task.due_date" class="text-gray-500">Completed at: {{ task.due_date }}</small>
                <p class="text-gray-600 mt-1">{{ task.description }}</p>
              </div>
              <div class="flex items-center space-x-4">
                <button 
                  @click="openEditModal(task)"
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

        <!-- No Tasks Found -->
        <div v-if="!loading && (!tasks.data || tasks.data.length === 0)" class="mt-6 text-center">
          <p class="text-gray-500">No tasks found.</p>
        </div>

        <!-- Modal Form -->
        <TaskFormModal
          :show="showModal"
          :editing-task="editingTask"
          @close="closeModal"
          @save="handleSave"
        />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, watchEffect } from 'vue'
import { useTaskStore } from '@/stores/useTaskStore'
import { useToast } from "vue-toastification"
import axios from 'axios'
import TaskFilters from '@/Components/Tasks/Filter.vue'
import TaskFormModal from '@/Components/Tasks/FormModal.vue'

const toast = useToast()
const taskStore = useTaskStore()
const tasks = ref({ data: [], meta: null })
const loading = ref(false)
const showModal = ref(false)
const editingTask = ref(null)

const handleSave = async (formData) => {
  try {
    if (editingTask.value) {
      await axios.put(`/api/tasks/${editingTask.value.id}`, formData)
      toast.success('Task updated successfully!')
    } else {
      await axios.post('/api/tasks', formData)
      toast.success('Task created successfully!')
    }
    await fetchTasks()
  } catch (error) {
    toast.error('An error occurred')
    throw error
  }
}

const closeModal = () => {
  showModal.value = false
  editingTask.value = null
}

const openCreateModal = () => {
  editingTask.value = null
  showModal.value = true
}

const openEditModal = (task) => {
  editingTask.value = task
  showModal.value = true
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
  
  