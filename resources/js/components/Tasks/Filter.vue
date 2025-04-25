<script setup>
import { useTaskStore } from '@/stores/useTaskStore'

const taskStore = useTaskStore()
</script>

<template>
  <div class="flex flex-wrap gap-4 mb-6">
    <select 
      v-model="taskStore.sortBy" 
      class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
      <option value="created_at">Sort by Date</option>
      <option value="title">Sort by Title</option>
    </select>

    <select 
      v-model="taskStore.sortDir" 
      class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
      <option value="asc">Ascending</option>
      <option value="desc">Descending</option>
    </select>

    <select 
      v-model="taskStore.completed" 
      class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    >
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
</template> 