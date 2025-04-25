<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <div class="flex space-x-8">
            <Link 
              href="/dashboard"
              class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              :class="[$page.url === '/dashboard' 
                ? 'border-indigo-500 text-gray-900' 
                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700']"
            >
              Dashboard
            </Link>
            
            <Link 
              href="/"
              class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              :class="[$page.url === '/'
                ? 'border-indigo-500 text-gray-900' 
                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700']"
            >
              Tasks
            </Link>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template> 